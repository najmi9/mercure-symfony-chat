/**
 * 
 * @param {string} url 
 * @param {string} method 
 * @param {string} data 
 * @returns 
 */
const jsonLdFetch  = async (url, method = 'GET', data=null) => {
    const params = {
        method: method,
        // credentails: 'include': Always send user credentials (cookies, basic http auth, etc..), even for cross-origin calls.
        credentails: 'same-origin', // by default
        headers:{
            'Accept': 'application/ld+json',
            'Content-Type': 'application/json'
        }
    }
    if (data) {
        params.body = data
    }

    const response = await fetch(url, params);
    if (response.status === 204) {
        return null;
    }

    const responseData = await response.json();

    if (response.ok) {
        return responseData;
    } else {
        throw responseData;
    }
}

export default jsonLdFetch;