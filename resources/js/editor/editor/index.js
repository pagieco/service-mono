import store from '../state/store';
import http from '../services/http';
import { domSerialize } from '../dom/serialize';
import { fetchHeadData } from '../libraries/data';
import { segment as urlSegment } from '../libraries/url';
import { getIframeDocument } from '../iframe';

/**
 * Create the editor-wrapper to place the editor in.
 *
 * @returns {void}
 */
function createEditorWrapper() {
  const editorWrapper = document.createElement('div');
  editorWrapper.id = 'editor-wrapper';

  const { body } = document;

  body.insertBefore(editorWrapper, body.firstChild);
}

/**
 * Get the toolbar height.
 *
 * @returns {number}
 */
export function getToolbarHeight() {
  return document.querySelector('.frame__top-bar').offsetHeight;
}

/**
 * Get the sidebar width.
 *
 * @returns {number}
 */
export function getSidebarWidth() {
  return document.querySelector('.frame__navigation').offsetWidth;
}

/**
 * Publish the current page's state.
 *
 * @returns {AxiosPromise<any>}
 */
export function publish() {
  const dom = domSerialize(getIframeDocument().querySelector('.page-wrapper'));
  const css = store.getters.rules;
  const pageId = urlSegment(3);

  return http.put(`pages/${pageId}/publish`, { dom, css });
}

/**
 * Setup the editor.
 *
 * @returns {void}
 */
export function setupEditor() {
  createEditorWrapper();
}
