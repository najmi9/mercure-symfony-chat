const users_url = '/api/users';
const convs_url = '/api/convs';
const msgs_url = (conv) => (`/api/convs/${conv}/msgs`);
const new_conv_url = (id) => (`/api/convs/new/${id}`);
const new_msg_url = (id) => (`/api/convs/${id}/msgs/new`);
const hub_url = 'http://localhost:3000/.well-known/mercure';
const msgTopic = (conv, userId) => (`http://mywebsite.com/msg/${conv}/users/${userId}`);
const convTopic = (id) => (`http://mywebsite.com/convs/${id}`);

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