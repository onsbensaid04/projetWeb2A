// virustotal-check.js

// Replace this with your actual VirusTotal API key
const VIRUSTOTAL_API_KEY = 'ad272a454d33f0ed74ecc9f59092270282f2f96aa0a5b5ea7f575f89253a8ee5';

/**
 * Extracts all URLs from the message text.
 * @param {string} text
 * @returns {string[]} Array of extracted URLs
 */
function extractUrls(text) {
    const urlRegex = /https?:\/\/[^\s]+/g;
    return text.match(urlRegex) || [];
}

/**
 * Submits a URL to VirusTotal for analysis and fetches its results.
 * @param {string} url
 * @returns {Promise<object>} analysis result
 */
async function checkUrlWithVirusTotal(url) {
    try {
        // Step 1: Submit the URL
        const scanResponse = await fetch('https://www.virustotal.com/api/v3/urls', {
            method: 'POST',
            headers: {
                'x-apikey': VIRUSTOTAL_API_KEY,
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `url=${encodeURIComponent(url)}`
        });

        const scanData = await scanResponse.json();
        const analysisId = scanData.data.id;

        // Step 2: Get the report using the analysis ID
        const reportResponse = await fetch(`https://www.virustotal.com/api/v3/analyses/${analysisId}`, {
            method: 'GET',
            headers: {
                'x-apikey': VIRUSTOTAL_API_KEY
            }
        });

        const result = await reportResponse.json();
        return result;
    } catch (error) {
        console.error('VirusTotal error:', error);
        return null;
    }
}

/**
 * Main function to check the message for URLs and analyze them.
 * @param {string} messageText
 * @returns {Promise<object>} { safe: boolean, url?: string, stats?: object }
 */
export async function checkMessageForUrls(messageText) {
    const urls = extractUrls(messageText);
    if (urls.length === 0) {
        return { safe: true }; // No URLs = safe
    }

    for (const url of urls) {
        const result = await checkUrlWithVirusTotal(url);
        if (result && result.data?.attributes?.stats) {
            const stats = result.data.attributes.stats;
            if (stats.malicious > 0 || stats.suspicious > 0) {
                return { safe: false, url, stats };
            }
        } else {
            return { safe: false, url, error: 'API error or invalid response' };
        }
    }

    return { safe: true }; // All URLs passed
}
