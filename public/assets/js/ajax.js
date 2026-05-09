// public/assets/js/ajax.js

const AjaxHelper = {
    /**
     * Send a POST request via Fetch API
     * @param {string} url - Target URL
     * @param {object} data - Data payload (will be converted to FormData if not already)
     * @returns {Promise}
     */
    post: async function(url, data) {
        let formData;
        if (data instanceof FormData) {
            formData = data;
        } else {
            formData = new FormData();
            for (const key in data) {
                formData.append(key, data[key]);
            }
        }

        try {
            const response = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            return await response.json();
        } catch (error) {
            console.error('AJAX Error:', error);
            throw error;
        }
    },

    /**
     * Send a GET request via Fetch API
     * @param {string} url - Target URL
     * @returns {Promise}
     */
    get: async function(url) {
        try {
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            return await response.json();
        } catch (error) {
            console.error('AJAX Error:', error);
            throw error;
        }
    }
};
