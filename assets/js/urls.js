
// Api Endpoints
const users_url = '/api/users';
const convs_url = '/api/convs';
const msgs_url = conv => (`/api/convs/${conv}/msgs`);
const new_conv_url = id => (`/api/convs/new/${id}`);
const new_msg_url = id => (`/api/convs/${id}/msgs/new`);
const conv_url = id => (`/api/convs/${id}`);
const delete_conv = id => (`/api/convs/${id}/delete`);

// Mercure Hub Url
const hub_url = 'http://localhost:3000/.well-known/mercure';

//Mercure Topic
const msgTopic = (convId) => (`/msgs/${convId}`);
const convTopic = (userId) => (`/convs/${userId}`);

export {
    convs_url,
    msgs_url,
    new_conv_url,
    new_msg_url,
    msgTopic,
    convTopic,
    hub_url,
    users_url,
    conv_url,
    delete_conv
}