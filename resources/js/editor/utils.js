export const fetchHeadData = element => JSON.parse(document.getElementById(element).innerHTML);

export const objectClean = obj => JSON.parse(JSON.stringify(obj));
