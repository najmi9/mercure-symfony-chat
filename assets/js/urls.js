
// Api Endpoints
export const users_url = '/api/users';

export const convs_url = '/api/convs';

export const msgs_url = conv => (`/api/convs/${conv}/msgs`);

export const new_conv_url = id => (`/api/convs/new/${id}`);

export const new_msg_url = id => (`/api/convs/${id}/msgs/new`);

export const edit_msg_url = id => (`/api/messages/${id}/update`);

export const delete_msg_url = id => (`/api/messages/${id}/delete`);

export const conv_url = id => (`/api/convs/${id}`);

export const delete_conv = id => (`/api/convs/${id}/delete`);

// Mercure Hub Url
export const hub_url = 'https://najmidev.tech/.well-known/mercure';

//Mercure Topic
export const msgTopic = (convId) => (`/msgs/${convId}`);

export const convTopic = (userId) => (`/convs/${userId}`);

export const avatau_url = '/build/images/default-avatar.jpeg';

export const userImage = picture => picture ? `/uploads/users/${picture}` : avatau_url;