
// Api Endpoints
export const users_url = '/api/users';

export const convs_url = '/api/conversations';

export const msgs_url = id => (`/api/conversations/${id}/msgs`);

export const new_conv_url = id => (`/api/conversations/new/${id}`);

export const new_msg_url = id => (`/api/conversations/${id}/msgs/new`);

export const edit_msg_url = id => (`/api/messages/${id}/update`);

export const delete_msg_url = id => (`/api/messages/${id}/delete`);

export const conv_url = id => (`/api/conversations/${id}`);

export const delete_conv = id => (`/api/conversations/${id}/delete`);

// Mercure Hub Url
export const hub_url = 'http://localhost:3000/.well-known/mercure';

//Mercure Topic
export const msgTopic = (convId) => (`/msgs/${convId}`);

export const convTopic = (userId) => (`/conversations/${userId}`);

export const avatau_url = '/build/images/default-avatar.jpeg';

export const userImage = pic => pic ? `/uploads/users/${pic}` : avatau_url;
