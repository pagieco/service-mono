import { pick, mapValues, forOwn } from 'lodash';
import { getIframeDocument } from '../iframe';
import { getNodeElement, getNodeRect } from '../dom';
import store from '../state/store';
import { getSidebarWidth, getToolbarHeight } from '../editor';
import event, { HIGHLIGHTER_RECALCPOS } from '../services/event';
import { px } from '../libraries/units';

/**
 * Get the position of the given node.
 *
 * @param  {string} id
 * @param  {Document} document
 * @return {{top: number, left: number, width: number, height: number}}
 */
export function getPositionForNode(id, document = getIframeDocument()) {
  return pick(
    mapValues(getNodeRect(id, document).toJSON(), r => px(r)),
    'top', 'left', 'width', 'height',
  );
}

/**
 * Reposition the highlighter elements.
 *
 * @param   {HTMLElement} wrapperElement
 * @returns {void}
 */
export function repositionHighlighters(wrapperElement) {
  wrapperElement.querySelectorAll('.highlighter-element').forEach((el) => {
    const pos = getPositionForNode(el.dataset.nodeRef);

    forOwn(pos, (value, prop) => {
      el.style[prop] = value;
    });
  });
}

/**
 * Handle the onMouseMove event.
 *
 * @param   {Event} e
 * @returns {void}
 */
function onMouseMove(e) {
  const { startPosition, currentHandle, selectionDimensions } = store.getters.resizingProperties;

  const sidebarWidth = getSidebarWidth();
  const toolbarHeight = getToolbarHeight();

  forOwn(selectionDimensions, ({ width, height }, nodeId) => {
    if (currentHandle === 'width') {
      // Get the relative resize width. This is the width that gets
      // added of subtracted from the element's width.
      const relativeWidthResize = e.clientX - startPosition.x + sidebarWidth;

      getNodeElement(nodeId).style.width = px(width + relativeWidthResize);
    } else if (currentHandle === 'height') {
      getNodeElement(nodeId).style.height = px(height + (e.clientY - startPosition.y + toolbarHeight));
    }
  });

  // Trigger the position recalculation event.
  event.$emit(HIGHLIGHTER_RECALCPOS);
}

/**
 * Handle the onMouseUp event.
 *
 * @returns {void}
 */
function onMouseUp() {
  // If the user WAS resizing (the stop, resizing action isn't yet dispatched).
  if (store.getters.isResizing) {
    const { currentHandle, selectionDimensions } = store.getters.resizingProperties;

    Object.keys(selectionDimensions).forEach((nodeId) => {
      const node = getNodeElement(nodeId);

      store.dispatch('setNodeStyleProp', {
        nodeId,
        property: currentHandle,
        value: node.style[currentHandle],
      });

      node.removeAttribute('style');
    });

    store.dispatch('stopResizing');
  }
}

/**
 * Bind the resize event handlers.
 *
 * @returns {void}
 */
export function bindResizeEventHandlers() {
  getIframeDocument().addEventListener('mousemove', (e) => {
    if (store.getters.isResizing) {
      onMouseMove(e);
    }
  });

  // Handle the mouse-up event on both the page document and the
  // iframe's document. This is because, when the user moves
  // the mouse faster then the event gets triggered the
  // mouse-up event will trigger on the page document
  // instead of the iframe document.
  [document, getIframeDocument()].forEach((doc) => {
    doc.addEventListener('mouseup', () => {
      onMouseUp();
    });
  });
}
