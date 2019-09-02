import { getNodeId, getTextContents } from './index';

/**
 * @param   {NamedNodeMap} attributes
 * @returns {*}
 */
function getNodeAttributes(attributes) {
  const skipAttributes = [
    'data-id', 'class',
  ];

  return Array.from(attributes)
    .filter(attr => !skipAttributes.includes(attr.name))
    .reduce((obj, param) => {
      obj[param.name] = param.value;

      return obj;
    }, {});
}

export function domSerialize(rootNode) {
  const tree = {
    uuid: getNodeId(rootNode),
    nodeType: rootNode.nodeName,
    textContent: getTextContents(rootNode),
    nodeAttributes: getNodeAttributes(rootNode.attributes),
    children: [],
  };

  // Recurse the node tree.
  const childNodes = rootNode.querySelectorAll(':scope > [data-id]');

  tree.children = [...childNodes].map(child => domSerialize(child));

  return tree;
}
