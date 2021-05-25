# Entitites:
1. User
2. Message
3. Conversation

In the entity `Message` we have:
--------------------------------

- **User**: Who create the message
- **Content**: Content of message
- **Conversation**: Conversation to whom the message belongs
- **Conversations**: Mapped By the field `lastMessage` in the conversation entity, so a conversation can have one ***lastMessage*** and Message can be the ***lastMessage*** of 2 conversations or more.

In the entity `User` we have:
-----------------------------
- **Data**: Name, Email, Passwoed, Enabled, Picture, ConfirmationToken and Roles
- **Messages**: The messages of the user
- **Conversations**:  Where the user participates

In the entity `Conversation` we have:
-------------------------------------

- **Users**: The participants of the conversation
- **LastMessage**: The last message of the conversation
- **Messages**: The messages of the conversation
- **OwnerId**:  Who created the conversation
# Controllers:


1. HomeController: Render the home page with `$response->setCookie($userCookie);`
1. SecurityController: Login, logout and reset passsword functionnalities.
1. RegistrationController: Register the use and send email to verify the account.
1. UsersController: Get all users.
1. MessageController.
1. ConversationController.

In the `ConversationController` we have:



CERT BOOT