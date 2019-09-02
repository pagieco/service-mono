import { getSidebarWidth, getToolbarHeight } from '../editor';
import { px } from '../libraries/units';
import event, { IFRAME_LOADED } from '../services/event';

/**
 * Get the iframe.
 *
 * @returns {HTMLElement}
 */
function getIframeElement() {
  return document.getElementById('canvas-frame');
}

/**
 * Get the iframe's document.
 *
 * @param   {HTMLElement | null} iframe
 * @returns {Document}
 */
export function getIframeDocument(iframe = null) {
  const element = iframe || getIframeElement();

  return element.contentDocument || element.contentWindow.document;
}

/**
 * Get the iframe's window object.
 *
 * @param  {HTMLElement | null} iframe
 * @return {WindowProxy}
 */
export function getIframeWindow(iframe = null) {
  return (iframe || getIframeElement()).contentWindow;
}

/**
 * Wrap the page into an iframe element.
 *
 * @param   {String} selector
 * @returns {void}
 */
export function wrapPageIntoIframe(selector) {
  const iframe = document.createElement('iframe');

  iframe.id = 'canvas-frame';
  iframe.classList.add('w-full', 'h-full', 'bg-white', 'border-0');

  iframe.addEventListener('load', () => {
    const { head, body } = iframe.contentWindow.document;

    head.appendChild(document.querySelector('#page-css'));
    body.appendChild(body.appendChild(document.querySelector('.page-wrapper')));

    document.querySelectorAll('script[data-id]').forEach((el) => {
      body.appendChild(el);
    });

    setTimeout(() => {
      document.documentElement.style.setProperty('--sidebar-width', px(getSidebarWidth()));
      document.documentElement.style.setProperty('--toolbar-height', px(getToolbarHeight()));

      event.$emit(IFRAME_LOADED);
    }, 100);
  }, false);

  document.querySelector(selector).appendChild(iframe);
}
