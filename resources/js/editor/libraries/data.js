export function fetchHeadData(id, defaultReturnValue = null) {
  const element = document.getElementById(id);

  return element
    ? JSON.parse(element.innerHTML)
    : defaultReturnValue;
}

export const objectClean = obj => JSON.parse(JSON.stringify(obj));
