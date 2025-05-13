
let currentChannel = 'general';
let currentChannelId = null;
const currentUserId = 1;
let editingMessageId = null;

const escapeHTML = (text) => {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
};


const formatTimestamp = (timestamp) => {
    const date = new Date(timestamp);
    return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
};


const loadChannels = async () => {
    try {
        const response = await fetch("/integration/Controller/getchannels.php");


        const channels = await response.json();
        const sidebar = document.getElementById('channelSidebar');
        sidebar.innerHTML = '';

        if (!Array.isArray(channels) || channels.length === 0) {
            sidebar.innerHTML = '<div class="channel-icon"><p>No channels found.</p></div>';
            return;
        }

        channels.forEach(channel => {
            const div = document.createElement('div');
            div.classList.add('channel-icon');
            div.dataset.name = channel.name;
            div.dataset.id = channel.id;
            div.innerHTML = `<img src="${channel.image_url || 'img/python.png'}" alt="${channel.name}">`;
            div.addEventListener('click', () => switchChannel(channel.name, channel.id));
            sidebar.appendChild(div);
        });


        switchChannel(channels[0].name, channels[0].id);
    } catch (error) {
        console.error('Error loading channels:', error);
        document.getElementById('channelSidebar').innerHTML =
            '<div class="channel-icon"><p>Error loading channels.</p></div>';
    }
};


const switchChannel = (name, channelId) => {
    currentChannel = name;
    currentChannelId = channelId;

    const titleElement = document.getElementById('channelTitle');
    if (titleElement) {
        titleElement.textContent = `# ${name}`;
    }

    const inputElement = document.getElementById('messageInput');
    if (inputElement) {
        inputElement.placeholder = `Message #${name}`;
        inputElement.value = '';
    }

    const chatContainer = document.getElementById('chat-messages');
    if (chatContainer) {
        chatContainer.innerHTML = '';
    }

    const channelIcons = document.querySelectorAll('.channel-icon');
    channelIcons.forEach(icon => {
        if (icon.dataset.name === name) {
            icon.classList.add('active');
        } else {
            icon.classList.remove('active');
        }
    });

    fetchMessages();
};




const checkMessageWithSightengine = async (text) => {
    const apiUser = '356610247';     // Replace with your Sightengine API user
    const apiSecret = '4nvAD9FiQ2JfbWfQ5bAsH7rSkjd8J3yq'; // Replace with your Sightengine API secret

    const url = `https://api.sightengine.com/1.0/text/check.json?text=${encodeURIComponent(text)}&lang=fr&mode=standard&categories=profanity,spam,violence&api_user=${apiUser}&api_secret=${apiSecret}`;

    try {
        const response = await fetch(url);
        if (!response.ok) {
            const errorText = await response.text();
            throw new Error(`HTTP ${response.status}: ${errorText}`);
        }
        const data = await response.json();
        console.log('Sightengine result:', JSON.stringify(data, null, 2));
        return data;
    } catch (error) {
        console.error('Sightengine moderation error:', error);
        return null;
    }
};

const emojiMap = {
    ':smile:': '&#128522;', // 😊
    ':grin:': '&#128515;', // 😃
    ':joy:': '&#128514;', // 😂
    ':rofl:': '&#129315;', // 🤣
    ':smiley:': '&#128516;', // 😄
    ':laughing:': '&#128518;', // 😆
    ':wink:': '&#128521;', // 😉
    ':blush:': '&#128522;', // 😊
    ':heart_eyes:': '&#128525;', // 😍
    ':kissing:': '&#128535;', // 😗
    ':innocent:': '&#128519;', // 😇
    ':sunglasses:': '&#128526;', // 😎
    ':neutral:': '&#128528;', // 😐
    ':expressionless:': '&#128529;', // 😑
    ':unamused:': '&#128530;', // 😒
    ':sweat:': '&#128531;', // 😓
    ':pensive:': '&#128532;', // 😔
    ':confused:': '&#128533;', // 😕
    ':confounded:': '&#128534;', // 😖
    ':kissing_heart:': '&#128536;', // 😘
    ':relieved:': '&#128524;', // 😌
    ':heart:': '&#10084;&#65039;', // ❤️
    ':thumbsup:': '&#128077;', // 👍
    ':thumbsdown:': '&#128078;', // 👎
    ':fire:': '&#128293;', // 🔥
    ':tada:': '&#127881;', // 🎉
    ':eyes:': '&#128064;', // 👀
    ':thinking:': '&#129300;', // 🤔
    ':100:': '&#128175;', // 💯
    ':check:': '&#9989;', // ✅
    ':rocket:': '&#128640;', // 🚀
    ':star:': '&#11088;', // ⭐
    ':sparkles:': '&#10024;', // ✨
    ':ok_hand:': '&#128076;', // 👌
    ':wave:': '&#128075;', // 👋
    ':pray:': '&#128591;', // 🙏
    ':clap:': '&#128079;', // 👏
    ':muscle:': '&#128170;', // 💪
    ':trophy:': '&#127942;', // 🏆
    ':gift:': '&#127873;', // 🎁
    ':birthday:': '&#127874;', // 🎂
    ':bulb:': '&#128161;', // 💡
    ':warning:': '&#9888;&#65039;', // ⚠️
    ':question:': '&#10067;', // ❓
    ':exclamation:': '&#10071;', // ❗
    ':anger:': '&#128162;', // 💢
    ':zzz:': '&#128164;', // 💤
    ':dash:': '&#128168;', // 💨
    ':sweat_drops:': '&#128166;', // 💦
    ':notes:': '&#127926;', // 🎶
    ':speak_no_evil:': '&#128584;', // 🙈
    ':see_no_evil:': '&#128586;', // 🙉
    ':hear_no_evil:': '&#128585;' // 🙊
};

const parseEmojis = (text) => {
    // Replace shortcodes with HTML entity representations of emojis
    Object.entries(emojiMap).forEach(([code, entity]) => {
        text = text.replace(new RegExp(escapeRegExp(code), 'g'), entity);
    });

    return text;
};

const escapeRegExp = (string) => {
    return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
};

const fetchMessages = async () => {
    if (!currentChannelId) {
        console.error('Cannot fetch messages: No channel selected.');
        return;
    }

    try {
        const url = `/integration/controller/getmessageControler.php?action=fetchMessages&channel_id=${currentChannelId}`;

        console.log('Fetching messages with GET:', url);

        const response = await fetch(url, {
            method: 'GET',
            cache: 'no-cache'
        });

        const data = await response.json();
        console.log('Fetched data:', data);

        const chatContainer = document.getElementById('chat-messages');
        chatContainer.innerHTML = '';

        if (data.status === 'success' && Array.isArray(data.messages)) {
            data.messages.forEach(msg => {
                const messageDiv = document.createElement('div');
                messageDiv.className = 'message';
                messageDiv.dataset.messageId = msg.id;


                const username = msg.user_id === 100 ? 'Anonyme' : `User ${msg.user_id}`;


                const messageWithEmojis = parseEmojis(escapeHTML(msg.content));

                // Make URLs clickable
                const messageWithClickableLinks = messageWithEmojis.replace(
                    /(https?:\/\/[^\s]+)/g,
                    '<a href="$1" target="_blank">$1</a>'
                );

                messageDiv.innerHTML = `
                    <div class="message-header">
                        <span class="username">${username}</span>
                        <span class="timestamp">${formatTimestamp(msg.sent_at)}</span>
                        <div class="message-actions">
                            <button class="message-menu-button" onclick="toggleMessageMenu(${msg.id})">
                                <i class="fa fa-ellipsis-v"></i>
                            </button>
                            <div id="message-menu-${msg.id}" class="message-menu">
                                <ul>
                                    <li><a href="#" onclick="editMessage(${msg.id}); return false;"><i class="fa fa-edit"></i> Edit</a></li>
                                    <li><a href="#" onclick="deleteMessage(${msg.id}); return false;"><i class="fa fa-trash"></i> Delete</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="message-content">${messageWithClickableLinks}</div>
                `;
                chatContainer.appendChild(messageDiv);
            });

            chatContainer.scrollTop = chatContainer.scrollHeight;
        } else {
            console.warn('No messages or failed fetch:', data.message);
            chatContainer.innerHTML = `<div class="no-messages">${escapeHTML(data.message || 'No messages yet.')}</div>`;
        }
    } catch (error) {
        console.error('Network error while fetching messages:', error);
        document.getElementById('chat-messages').innerHTML =
            '<div class="error">Error loading messages. Please try again later.</div>';
    }
};

const sendMessage = async (e) => {
    if (e) e.preventDefault();

    const input = document.getElementById('messageInput');
    let messageText = input.value.trim();

    // Check if the anonymous toggle is enabled
    const isAnonymous = document.getElementById('anonymousToggle').checked;
    const userId = isAnonymous ? 100 : currentUserId;
    if (!currentChannelId) {
        console.error('Cannot send message: No channel selected.');
        return;
    }

    if (!messageText) {
        console.error('Cannot send empty message.');
        return;
    }


    const moderationResult = await checkMessageWithSightengine(messageText);
    console.log("Sightengine result:", moderationResult);

    if (
        !moderationResult ||
        moderationResult.profanity?.matches?.length > 0 ||
        moderationResult.insult?.matches?.length > 0 ||
        moderationResult.toxicity?.matches?.length > 0 ||
        moderationResult.threat?.matches?.length > 0 ||
        moderationResult.personal?.matches?.length > 0
    ) {
        alert('Message bloqué pour contenu inapproprié.');
        input.value = '';


        await fetch('/integration/controller/log_message.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                type: 'blocked',
                user_id: userId,
                channel_id: currentChannelId,
                content: messageText,
                timestamp: new Date().toISOString()
            })
        });

        return;
    }

    const vtResult = await checkMessageWithVirusTotal(messageText);
    if (!vtResult.safe) {
        alert(`Message bloqué : le lien "${vtResult.url}" est signalé comme dangereux.`);
        input.value = '';


        await fetch('/integration/controller/log_message.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                type: 'blocked',
                user_id: userId,
                channel_id: currentChannelId,
                content: messageText,
                timestamp: new Date().toISOString()
            })
        });

        return;
    }


    if (editingMessageId) {
        try {
            const url = `/integration/controller/updateMessageControler.php?id=${editingMessageId}&content=${encodeURIComponent(messageText)}`;
            const response = await fetch(url, {
                method: 'GET',
                cache: 'no-cache'
            });

            const data = await response.json();
            console.log('Update response:', data);

            if (data.success) {
                input.value = '';
                input.placeholder = `Message #${currentChannel}`;
                editingMessageId = null;
                fetchMessages();
            } else {
                alert('Échec de la mise à jour du message : ' + data.message);
            }
        } catch (error) {
            console.error('Erreur lors de la mise à jour du message :', error);
        }
        return;
    }


    try {
        console.log("Sending message:", messageText, currentChannelId, userId);

        const url = `/integration/controller/sendmessagecontroler.php?action=sendMessage&channel_id=${currentChannelId}&user_id=${userId}&content=${encodeURIComponent(messageText)}`;

        const response = await fetch(url, {
            method: 'GET',
            cache: 'no-cache'
        });

        const data = await response.json();
        console.log(data);

        if (data.status === 'success') {
            input.value = '';
            hideEmojiSuggestions();
            fetchMessages();


            await fetch('/integration/controller/log_message.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    type: 'sent',
                    user_id: userId,
                    channel_id: currentChannelId,
                    content: messageText,
                    timestamp: new Date().toISOString()
                })
            });
        } else {
            console.error('Erreur lors de l\'envoi :', data.message || 'Erreur inconnue');
        }
    } catch (error) {
        console.error('Erreur réseau lors de l\'envoi :', error);
    }
};




let showingSuggestions = false;
let emojiSearchText = '';
let selectedSuggestionIndex = -1;

const initEmojiAutocomplete = () => {
    const inputElement = document.getElementById('messageInput');

    // Create emoji suggestions container
    const emojiSuggestionsDiv = document.createElement('div');
    emojiSuggestionsDiv.id = 'emoji-suggestions';
    emojiSuggestionsDiv.className = 'emoji-suggestions';
    emojiSuggestionsDiv.style.display = 'none';
    document.body.appendChild(emojiSuggestionsDiv);


    inputElement.addEventListener('input', handleInputChange);
    inputElement.addEventListener('keydown', handleInputKeydown);


    document.addEventListener('click', (e) => {
        if (e.target !== inputElement && e.target.closest('#emoji-suggestions') === null) {
            hideEmojiSuggestions();
        }
    });
};


const handleInputChange = (e) => {
    const inputElement = e.target;
    const text = inputElement.value;
    const cursorPos = inputElement.selectionStart;

    let colonPos = text.lastIndexOf(':', cursorPos - 1);

    if (colonPos !== -1 && text.substring(colonPos, cursorPos).indexOf(' ') === -1) {
        // Extract search text (without the colon)
        emojiSearchText = text.substring(colonPos + 1, cursorPos);

        if (emojiSearchText.length > 0) {
            showEmojiSuggestions(emojiSearchText, inputElement);
        } else {
            hideEmojiSuggestions();
        }
    } else {
        hideEmojiSuggestions();
    }
};


const handleInputKeydown = (e) => {
    if (!showingSuggestions) return;

    const suggestionsContainer = document.getElementById('emoji-suggestions');
    const suggestions = suggestionsContainer.querySelectorAll('div');

    switch (e.key) {
        case 'ArrowDown':
            e.preventDefault();
            selectedSuggestionIndex = Math.min(selectedSuggestionIndex + 1, suggestions.length - 1);
            updateSelectedSuggestion();
            break;

        case 'ArrowUp':
            e.preventDefault();
            selectedSuggestionIndex = Math.max(selectedSuggestionIndex - 1, 0);
            updateSelectedSuggestion();
            break;

        case 'Enter':
        case 'Tab':
            if (selectedSuggestionIndex >= 0 && selectedSuggestionIndex < suggestions.length) {
                e.preventDefault();
                insertSelectedEmoji();
            }
            break;

        case 'Escape':
            hideEmojiSuggestions();
            break;
    }
};


const showEmojiSuggestions = (searchText, inputElement) => {
    const suggestionsContainer = document.getElementById('emoji-suggestions');


    const filteredEmojis = Object.keys(emojiMap).filter(code =>
        code.substring(1, code.length - 1).toLowerCase().includes(searchText.toLowerCase())
    );


    if (filteredEmojis.length === 0) {
        hideEmojiSuggestions();
        return;
    }


    suggestionsContainer.innerHTML = '';


    const maxSuggestions = Math.min(filteredEmojis.length, 10);
    for (let i = 0; i < maxSuggestions; i++) {
        const code = filteredEmojis[i];
        const entity = emojiMap[code];

        const suggestionDiv = document.createElement('div');
        suggestionDiv.className = 'emoji-suggestion';
        suggestionDiv.innerHTML = `<span class="emoji-preview">${entity}</span><span class="emoji-code">${code}</span>`;
        suggestionDiv.dataset.emojiCode = code;

        suggestionDiv.addEventListener('click', () => {
            insertEmoji(code);
        });

        suggestionsContainer.appendChild(suggestionDiv);
    }


    const inputRect = inputElement.getBoundingClientRect();
    suggestionsContainer.style.top = `${inputRect.bottom}px`;
    suggestionsContainer.style.left = `${inputRect.left}px`;
    suggestionsContainer.style.display = 'block';

    // Reset selection
    selectedSuggestionIndex = -1;

    showingSuggestions = true;
};

const hideEmojiSuggestions = () => {
    const suggestionsContainer = document.getElementById('emoji-suggestions');
    suggestionsContainer.style.display = 'none';
    showingSuggestions = false;
    selectedSuggestionIndex = -1;
};

const updateSelectedSuggestion = () => {
    const suggestions = document.querySelectorAll('.emoji-suggestion');


    suggestions.forEach(suggestion => {
        suggestion.classList.remove('selected');
    });

    if (selectedSuggestionIndex >= 0 && selectedSuggestionIndex < suggestions.length) {
        suggestions[selectedSuggestionIndex].classList.add('selected');
        // Make sure the selected item is visible
        suggestions[selectedSuggestionIndex].scrollIntoView({ block: 'nearest' });
    }
};

const insertSelectedEmoji = () => {
    const suggestions = document.querySelectorAll('.emoji-suggestion');
    if (selectedSuggestionIndex >= 0 && selectedSuggestionIndex < suggestions.length) {
        const selectedCode = suggestions[selectedSuggestionIndex].dataset.emojiCode;
        insertEmoji(selectedCode);
    }
};

const insertEmoji = (emojiCode) => {
    const inputElement = document.getElementById('messageInput');
    const text = inputElement.value;
    const cursorPos = inputElement.selectionStart;

    const colonPos = text.lastIndexOf(':', cursorPos - 1);

    if (colonPos !== -1) {
        // Replace the partial emoji code with the full code
        const newText = text.substring(0, colonPos) + emojiCode + text.substring(cursorPos);
        inputElement.value = newText;


        const newCursorPos = colonPos + emojiCode.length;
        inputElement.setSelectionRange(newCursorPos, newCursorPos);
    }

    hideEmojiSuggestions();
    inputElement.focus();
};



// Initialize emoji autocomplete when the DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    initEmojiAutocomplete();

    // Add CSS styles for emoji suggestions
    const styleElement = document.createElement('style');
    styleElement.textContent = `
        .emoji-suggestions {
            position: absolute;
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            width: 250px;
        }
        
        .emoji-suggestion {
            padding: 8px 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
        }
        
        .emoji-suggestion:hover, .emoji-suggestion.selected {
            background-color: #f0f0f0;
        }
        
        .emoji-preview {
            font-size: 18px;
            margin-right: 10px;
            min-width: 24px;
            text-align: center;
        }
        
        .emoji-code {
            color: #666;
        }
    `;
    document.head.appendChild(styleElement);
});





const deleteMessage = async (messageId) => {
    if (!confirm('Are you sure you want to delete this message?')) {
        return;
    }

    try {
        const url = `/integration/controller/deleteMessageControler.php?id=${messageId}`;
        const response = await fetch(url, {
            method: 'GET',
            cache: 'no-cache'
        });

        const data = await response.json();
        console.log('Delete response:', data);

        if (data.success) {
            fetchMessages();
        } else {
            alert('Failed to delete message: ' + data.message);
        }
    } catch (error) {
        console.error('Error deleting message:', error);
    }
};

const editMessage = (messageId) => {
    const input = document.getElementById('messageInput');
    if (!input) return;

    editingMessageId = messageId;
    input.placeholder = 'Editing message...';
    input.focus();
};


async function checkMessageWithVirusTotal(messageText) {
    // Regex to detect URLs
    const urlRegex = /(https?:\/\/[^\s]+)/g;
    const urls = messageText.match(urlRegex);

    if (!urls || urls.length === 0) {
        return { safe: true }; // No URLs = Safe
    }

    const url = urls[0];
    try {
        const proxyUrl = `/integration/controller/virusTotal.php?url=${encodeURIComponent(url)}`;
        const res = await fetch(proxyUrl);
        const data = await res.json();

        const stats = data?.data?.attributes?.stats;

        if (stats?.malicious > 0 || stats?.suspicious > 0) {
            return { safe: false, url, stats };
        }

        return { safe: true };
    } catch (err) {
        console.error('Erreur lors de la vérification VirusTotal:', err);
        return { safe: false, url, error: err.message };
    }
}


document.addEventListener('DOMContentLoaded', () => {
    loadChannels();

    const messageInput = document.getElementById('messageInput');
    if (messageInput) {
        messageInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });
    }

    const sendButton = document.getElementById('sendMessageButton');
    if (sendButton) {
        sendButton.addEventListener('click', sendMessage);
    }
});
document.getElementById('sendMessageButton').addEventListener('click', sendMessage);



