import { uniq } from 'lodash';
import { getIframeDocument } from '../iframe';

export function reflectFontList(fontList) {
  const list = uniq(fontList).map(font => font.replace(/ /g, '+')).join('|');

  const webFonts = getIframeDocument().querySelector('#web-fonts');

  if (webFonts) {
    webFonts.parentNode.removeChild(webFonts);
  }

  const element = document.createElement('link');
  element.id = 'web-fonts';
  element.rel = 'stylesheet';
  element.href = `https://fonts.googleapis.com/css?family=${list}`;

  getIframeDocument().head.appendChild(element);
}
