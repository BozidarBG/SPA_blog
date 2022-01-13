Hello :)

This is an SPA blog, made in Laravel with Sanctum used for authentication.

All users can:

- see all articles (or texts as you can call it):
GET /api/v1/articles

- see single article:
  GET /api/v1/articles/slug of that article

- see all articles by genre:
GET /api/v1/articles?genre=pets

- see all genres:
GET /api/v1/genres

- see single genre:
GET /api/v1/genre/id of genre

- register (with sent email confirmation to user's address):
POST /api/v1/register; 
In form: name, email, password, password_confirmation, agree (true)

- request confirmation email to be resent:
POST /api/v1/resend-verification-email;
In form: email

- in case of forgotten password, request change password email to be sent;
POST /api/v1/reset-password;
In form: email

- login (and receive Bearer token):
POST /api/v1/login;
In form: email, password

In order to verify email address, user will receive email containing link to:  
GET /verify-email/{id}/{hash}


Authenticated users that are confirmed by email and not banned by admin can:
All requests must have Bearer and token received after login

- create new articles:
POST /api/v1/articles;
In form: title, body, genres (ids of genres)

- update their own articles:
POST /api/v1/articles/slug of an article;
In form: title, body, genres (ids of genres)

- delete their own articles:
POST /api/v1/delete-articles/slug of an article

- like or remove like from articles:
POST /api/v1/toggle-like/slug of an article

- post comments on articles:
POST /api/v1/comments;
In form: article_id and content

- edit their own comments:
POST /api/v1/comments/id;
In form: content

- delete their own comments:
  POST /api/v1/delete-comments/id

- see the list of their own articles:
GET /api/v1/my-articles

- see the list of their own comments:
GET /api/v1/my-comments

- upload avatar:
POST /api/v1/update-avatar;
In form: avatar (type is file)

- delete avatar:
 POST /api/v1/delete-avatar

- change password:
POST /api/v1/change-password;
In form: email, old_password, new_password, new_password_confirmation

- delete profile:
POST /api/v1/delete-profile;
In form: password

Administrator can:
- delete any article:
POST /api/v1/admin/delete-article/slug of the article

- delete any comment:
POST /api/v1/admin/delete-comment/id of that comment

- create new genre:
POST /api/v1/admin/genres;
In form: name, description

- update genre:
POST /api/v1/admin/genres/update/id of genre;
  In form: name, description

- delete genre:
POST /api/v1/admin/delete-genre/id of genre

- see the list of banned users:
 GET /api/v1/admin/banned-users

- ban users:
POST /api/v1/admin/ban-user;
In form: user_id, reason, plus_days (for how long user will be banned)

- remove ban from users:
POST /api/v1/admin/remove-ban/id of banned user


- see all users:
GET /api/v1/admin/users

Thank you :)
