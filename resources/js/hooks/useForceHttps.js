
export const useForceHttps = (url) => {
    if (!url) return url;
    if (import.meta.env.PROD) {
        return url.replace('http://', 'https://');
    }
    return url;
};