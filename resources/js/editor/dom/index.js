import Node from './Node';
import router from '../router';
import store from '../state/store';
import { v4 as uuid } from '../libraries/uuid';
import { getIframeDocument } from '../iframe';

/**
 * Create an HTML attribute if the value is not null.
 *
 * @param  {string | null} attribute
 * @return {string}
 */
export const attrIf = (attribute = null) => (attribute ? `="${attribute}"` : '');

/**
 * Create a selector string for selecting a node.
 *
 * @param  {string | number | null} id
 * @return {string}
 */
export function nodeSelector(id = null) {
  return `[data-id${attrIf(id)}]`;
}

/**
 * Get the id of the given HTMLElement.
 *
 * @param  {HTMLElement} el
 * @return {string | null}
 */
export function getNodeId(el) {
  return el.dataset.id || null;
}

/**
 * Get the HTML instance of a node by the given node id.
 *
 * @param  {string} id
 * @param  {Document} document
 * @return {Element | any}
 */
export function getNodeElement(id, document = getIframeDocument()) {
  return document.querySelector(nodeSelector(id));
}

/**
 * Collect all nodes matching the given selector.
 *
 * @param  {string} selector
 * @param  {Document | null} document
 * @return {NodeListOf<Element>}
 */
export function collectNodes(selector = nodeSelector(), document = null) {
  return (document || getIframeDocument()).querySelectorAll(selector);
}

/**
 * Retrieve the node's bounding client rect.
 *
 * @param  {String} id
 * @param  {Document} document
 * @return {ClientRect | DOMRect | null}
 */
export function getNodeRect(id, document = getIframeDocument()) {
  const element = getNodeElement(id, document);

  return element ? element.getBoundingClientRect() : null;
}

/**
 * Add a new node.
 *
 * @param   {array} selectionSet
 * @returns {Promise<any>}
 */
export function addNewNode(selectionSet) {
  const el = document.createElement('div');
  el.dataset.id = uuid();

  selectionSet.forEach((nodeId) => {
    const node = getNodeElement(nodeId);

    node.parentNode.insertBefore(el, node.nextSibling);
  });

  return store.dispatch('collectNodes', collectNodes());
}

/**
 * Reflect the current node selection on the DOM.
 *
 * @param   {array} selectionSet
 * @param   {string} selectedClassName
 * @returns {void}
 */
export function reflectNodeSelection(selectionSet, selectedClassName = 'selected') {
  // If nothing is selected then force the sidebar to go to the home route.
  if (selectionSet.length === 0) {
    router.push({ name: 'home' });
  }

  // Retrieve all the nodes from the iframe that have a matching selected className.
  getIframeDocument().querySelectorAll(`.${selectedClassName}`).forEach(node => (
    node.classList.remove(selectedClassName)
  ));

  // For each node from the selectionSet add the selected className.
  selectionSet.forEach(node => (
    getNodeElement(node).classList.add(selectedClassName)
  ));
}

/**
 * Get the text contents of a node.
 *
 * @param   {HTMLElement} node
 * @returns {string}
 */
export function getTextContents(node) {
  let textContent = '';

  for (let i = 0; i < node.childNodes.length; i++) {
    const currNode = node.childNodes[i];

    if (currNode.nodeName === '#text') {
      textContent = currNode.nodeValue.trim();
      break;
    }
  }

  return textContent;
}

export default Node;
