import { getNodeRect } from '../dom';
import { px } from '../libraries/units';
import { getSidebarWidth, getToolbarHeight } from '../editor';

function getEditorOverlayElement() {
  return document.getElementById('editor-overlay');
}

export function positionOverlay(id) {
  const overlay = getEditorOverlayElement();
  const overlayRect = overlay.getBoundingClientRect();
  const nodeRect = getNodeRect(id);
  const offset = 5;

  if (overlay.offsetHeight > nodeRect.y) {
    // position below.
    overlay.style.top = px(nodeRect.y + nodeRect.height + getToolbarHeight() + offset);
    overlay.style.left = px(nodeRect.x + getSidebarWidth() + offset);
  } else {
    // position above.
    overlay.style.top = px(nodeRect.y + getToolbarHeight() - overlayRect.height - offset);
    overlay.style.left = px(nodeRect.x + getSidebarWidth() + offset);
  }
}

export function showOverlay() {
  getEditorOverlayElement().classList.remove('hidden');
}

export function hideOverlay() {
  getEditorOverlayElement().classList.add('hidden');
}
