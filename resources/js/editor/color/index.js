import tinycolor from 'tinycolor2';

/**
 * Convert the given color to a `tinycolor` object.
 *
 * @param   {String} color
 * @returns {tinycolor}
 */
export const convertColorToObject = color => tinycolor(color);
