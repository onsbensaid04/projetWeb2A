export async function checkMessageWithSightengine(text) {
    const apiUser = '356610247';     // Replace with your Sightengine user
    const apiSecret = '4nvAD9FiQ2JfbWfQ5bAsH7rSkjd8J3yq'; // Replace with your Sightengine secret

    const url = `https://api.sightengine.com/1.0/text/check.json?text=${encodeURIComponent(text)}&lang=fr&mode=standard&categories=profanity,insult,toxicity,threat,personal&api_user=${apiUser}&api_secret=${apiSecret}`;

    try {
        const response = await fetch(url);
        if (!response.ok) {
            const errText = await response.text();
            throw new Error(`HTTP ${response.status}: ${errText}`);
        }
        const data = await response.json();
        console.log('Sightengine result:', data);
        return data;
    } catch (error) {
        console.error('Sightengine moderation error:', error);
        return null;
    }
}
