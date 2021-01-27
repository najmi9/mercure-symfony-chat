
// Api Endpoints
const users_url = '/api/users';
const convs_url = '/api/convs';
const msgs_url = (conv) => (`/api/convs/${conv}/msgs`);
const new_conv_url = (id) => (`/api/convs/new/${id}`);
const new_msg_url = (id) => (`/api/convs/${id}/msgs/new`);

// Mercure Hub Url
const hub_url = 'https://najmi-chat.herokuapp.com:26190/.well-known/mercure';

//Mercure Topic
const msgTopic = (convId) => (`http://mywebsite.com/msgs/${convId}`);
const convTopic = (userId) => (`http://mywebsite.com/convs/${userId}`);

export {
    convs_url,
    msgs_url,
    new_conv_url,
    new_msg_url,
    msgTopic,
    convTopic,
    hub_url,
    users_url
}