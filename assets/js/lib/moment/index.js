/**
 * @param {Date|string} date 
 */
 export default function fromNow(date) {
    let result = null;

    const ms = (new Date() - new Date(date));
    const s = Math.round(ms / 1000);
    const min = Math.round(s / 60);
    const h = Math.round(min / 60);
    const d = Math.round(h / 24);
    const w = Math.round(d / 7);
    const m = Math.round(w / 7);
    const y = Math.round(m / 12);

    if (s < 1) {
        result = 'Now';
    }

    if (s < 60 & s > 1) {
        result = `a ${s} seconds ago`;
    }

    if (min >= 1 & min < 60) {
        if (min === 1) {
            result = `a minute ago`;
        } else {
            result = `a ${min} minutes ago`;
        }
    }

    if (h >= 1 & h < 24) {
        if (h === 1) {
            result = `an hour ago`;
        } else {
            result = `a ${h} hours ago`;
        }
    }

    if (d >= 1 & d < 7) {
        if (d === 1) {
            result = `a day ago`;
        } else {
            result = `a ${d} days ago`;
        }
    }

    if (w >= 1 & w < 30 / 7) {
        if (w === 1) {
            result = `a week ago`;
        } else {
            result = `a ${w} weeks ago`;
        }
    }

    if (m >= 1 & m < 12) {
        if (m === 1) {
            result = `a month ago`;
        } else {
            result = `a ${m} months ago`;
        }
    }

    if (y >= 1) {
        if (y === 1) {
            result = `a year ago`;
        } else {
            result = `a ${y} years ago`;
        }
    }

    return result;
}