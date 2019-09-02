import store from '../state/store';
import { getNodeId } from '.';
import { hideOverlay, positionOverlay, showOverlay } from '../editor-overlay';

export default class Node {
  constructor(htmlElement) {
    this.htmlElement = htmlElement;
    this.id = getNodeId(htmlElement);

    this.bindEventHandlers();
  }

  bindEventHandlers() {
    this.htmlElement.addEventListener('click', e => this.onClick(e));
    this.htmlElement.addEventListener('dblclick', e => this.onDblClick(e));
    this.htmlElement.addEventListener('drop', e => this.onDrop(e));
  }

  /**
   * Handle the click event.
   *
   * @param {Event} e
   * @returns {void}
   */
  onClick(e) {
    e.stopPropagation();

    // If the user selects another element then the editor-overlay should be hidden.
    const { selectionSet } = store.getters;
    if (selectionSet.length > 1 || selectionSet[0] !== this.id) {
      hideOverlay();
    }

    store.dispatch('setNodeSelection', {
      originalEvent: e,
      nodeId: this.id,
    });
  }

  /**
   * Handle the double-click event.
   *
   * @param {Event} e
   * @returns {void}
   */
  onDblClick(e) {
    e.stopPropagation();

    showOverlay();
    positionOverlay(this.id);
  }

  /**
   * Handle the drop event.
   *
   * @param {Event} e
   * @returns {void}
   */
  onDrop(e) {
    e.stopPropagation();
    e.preventDefault();

    if (e.dataTransfer.items) {
      const item = e.dataTransfer.items[0];

      if (item.kind === 'file') {
        const file = item.getAsFile();
        const fileReader = new FileReader();

        fileReader.readAsDataURL(file);
        fileReader.onloadend = () => {
          this.htmlElement.style.backgroundImage = `url("${fileReader.result}")`;
        };
      }
    }
  }

  getPosition() {
    return this.htmlElement.getBoundingClientRect();
  }
}
