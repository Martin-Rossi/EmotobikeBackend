## Catalog API

# Models

**User**  

	route: /users  
	Properties:
		id (INT 10, primary key, autoincrement)
		parent (INT 10)
		group_id (INT 10, references id on 'user_groups')
		tags (VARCHAR 255, nullable, default: null)
		name (VARCHAR 255)
		email (VARCHAR 255, unique)
		password (VARCHAR 60, bcrypted)
		remember_token (VARCHAR 100)
		image (VARCHAR 255, nullable, default: null)
		profile_name (VARCHAR 255, nullable, default: null)
		profile_description (TEXT, nullable, default: null)
		api_paypal (VARCHAR 255, nullable, default: 0)
		api_loyalty (VARCHAR 255, nullable, default: 0)
		api_gift (VARCHAR 255, nullable, default: 0)
		commissions (DOUBLE (15,8), default: 0)
		comission_rate (DOUBLE (12,2), default: 0)
		commission_exchange (DOUBLE (12,2), default: 0)
		personal_price_earned (INT 10, default: 0)
		price_earner (INT 10, default: 0)
		count_likes (INT 10, default: 0)  
		count_following (INT 10, default: 0)  
		count_authored (INT 10, default: 0)  
		count_drafts (INT 10, default: 0)  
		count_follows (INT 10, default: 0)
		chat (ENUM [0,1])
		created_at (TIMESTAMP)
		updated_at (TIMESTAMP)  
		
**UserGroups**

	route: -
	Properties:
		id (INT 10, primary key)
		name (VARCHAR 55)
		caps (TEXT, nullable, default: null)
		created_at (TIMESTAMP)
		updated_at (TIMESTAMP) 
		
**UserPreferences**

	route:: /users/preferences
	Properties:
		id (INT 10, primary key, autoincrement)
		user_id (INT 10, references id on 'users')
		key (VARCHAR 55)
		value (ENUM [0,1])
		created_at (TIMESTAMP)
		updated_at (TIMESTAMP) 
		
**Object**  

	route: /objects
	Properties:
		id (INT 10, primary key, autoincrement)
		catalog_id (INT 10, references id on 'catalogs', default: 0)
		category_id (INT 10, references id on 'categories', default: 0)
		type_id (INT 10, references id on 'types', default: 0)
		tags (VARCHAR 255, nullable, default: null)
		name (VARCHAR 255)
		description (TEXT, nullable, default: null)
		url (VARCHAR 255, nullable, default: null)
		image (VARCHAR 255, nullable, default: null)
		weight (DOUBLE(12,4), nullable, default: null)
		retail_price (DOUBLE (12,2), nullable, default: null)
		sale_price (DOUBLE (12,2), nullable, default: null)
		offer_value (DOUBLE (12,2), nullable, default: null)
		offer_url (VARCHAR 255, nullable, default: null)
		offer_description (TEXT, nullable, default: null)
		offer_start (DATETIME YYYY-MM-DD HH:II:SS)
		offer_stop (DATETIME YYYY-MM-DD HH:II:SS)
		prod_detail_url (VARCHAR 255, nullable, default: null)
		layout (VARCHAR 55, nullable, default: null)
		position (VARCHAR 55, nullable, default: null)
		count_likes (INT 10, default: 0)
		count_comments (INT 10, default: 0)
		count_follows (INT 10, default: 0)
		count_recommended (INT 10, default: 0)
		competitor_flag (ENUM[0,1], default: 0)
		curated (ENUM[0,1], default: 0)
		author (INT 10, references id on 'users')
		status (ENUM[-1,0,1], default: 1)
		created_at (TIMESTAMP)
		updated_at (TIMESTAMP)
		
**Catalog**  

	route: /catalogs
	Properties:
		id (INT 10, primary key, autoincrement)
		category_id (INT 10, references id on 'categories', default: 0)
		type_id (INT 10, references id on 'types', default: 0)
		tags (VARCHAR 255, nullable, default: null)
		name (VARCHAR 255)
		title (VARCHAR 255)
		description (TEXT, nullable, default: null)
		image (VARCHAR 255, nullable, default: null)
		layout (VARCHAR 55, nullable, default: null)
		position (VARCHAR 55, nullable, default: null)
		publish (ENUM[0,1], default: 0)
		trending (ENUM[0,1], default: 0)
		popular (ENUM[0,1], default: 0)
		count_likes (INT 10, default: 0)
		count_comments (INT 10, default: 0)
		count_follows (INT 10, default: 0)
		count_recommended (INT 10, defualt: 0)
		total_transaction (DOUBLE(15,8), default: 0)  
		earning_trend (INT 10, default: 0)  
		earning_total (DOUBLE(12,2), default: 0)  
		earning_place (INT 10, default: 0)  
		earning_cat_place (INT 10, default: 0)  
		earning_potential (INT 10, default: 0)
		author (INT 10, references id on 'users')
		status (ENUM[-1,0,1], default: 1)
		created_at (TIMESTAMP)
		updated_at (TIMESTAMP)  
		
**Collection**  

	route: /collections
	Properties:
		id (BIGINT 20, primary key, autoincrement)
		collection_id (INT 10)
		foreign_id (INT 10)
		foreign_type (ENUM['object','catalog'])
		author (INT 10, references id on 'users')
		status (ENUM[-1,0,1], default: 1)
		created_at (TIMESTAMP)
		updated_at (TIMESTAMP)  
		
**GenericCollection**  

	route: /generic-collections
	Properties:
		id (BIGINT 20, primary key, autoincrement)
		collection_id (INT 10)
		foreign_id (INT 10)
		foreign_type (ENUM['object','catalog'])
		status (ENUM[-1,0,1], default: 1)
		created_at (TIMESTAMP)
		updated_at (TIMESTAMP)  
		
**Route**  

	route: /routes
	Properties:
		id (INT 10, primary key, autoincrement)
		name (VARCHAR 255)
		description (TEXT, nullable, default: null)
		data (BLOB, nullable, default: null)
		object_ids (TEXT, nullable, default: null)
		author (INT 10, references id on 'users')
		status (ENUM[-1,0,1], default: 1)
		created_at (TIMESTAMP)
		updated_at (TIMESTAMP) 
		
**Category**

	route: /categories
	Properties:
		id (INT 10, primary key, autoincrement)
		name (VARCHAR 55)
		created_at (TIMESTAMP)
		updated_at (TIMESTAMP)  
		
**Types**

	route: /types
	Properties:
		id (INT 10, primary key, autoincrement)
		name (VARCHAR 55)
		created_at (TIMESTAMP)
		updated_at (TIMESTAMP)  
		
**Comments**  

	route: /comments
	Properties:
		id (INT 10, primary key, autoincrement)
		foreign_id (INT 10)
		foreign_type (ENUM['object','catalog'])
		text (TEXT)
		author (INT 10, references id on 'users')
		created_at (TIMESTAMP)
		updated_at (TIMESTAMP)  
		
**Likes**  

	route: /likes
	Properties:
		id (INT 10, primary key, autoincrement)
		foreign_id (INT 10)
		foreign_type (ENUM['object','catalog'])
		author (INT 10, references id on 'users')
		created_at (TIMESTAMP)
		updated_at (TIMESTAMP)  
		
**Follows**  

	route: /follows
	Properties:
		id (INT 10, primary key, autoincrement)
		foreign_id (INT 10)
		foreign_type (ENUM['object','catalog'])
		author (INT 10, references id on 'users')
		created_at (TIMESTAMP)
		updated_at (TIMESTAMP) 
		
**Recommendations**  

	route: -
	Properties:
		id (INT 10, primary key, autoincrement)
		foreign_id (INT 10)
		foreign_type (ENUM['object','catalog'])
		author (INT 10, references id on 'users')
		created_at (TIMESTAMP)
		updated_at (TIMESTAMP) 
		
**Friends**

	route: /friends
	Properties:
		id (INT 10, primary key, autoincrement)
		from_id (INT 10, references id on 'users' - unique with 'to_id')
		from_accepted (ENUM [0, 1])
		to_id (INT 10, references id on 'users' - unique with 'from_id')
		to_accepted (ENUM [0, 1])
		created_at (TIMESTAMP)
		updated_at (TIMESTAMP) 
		
**Feedbacks**  

	route: /feedbacks
	Properties:
		id (INT 10, primary key, autoincrement)
		foreign_id (INT 10)
		foreign_type (ENUM['object','catalog'])
		product_id (INT 10, default: 0)  
		offer_id (INT 10, default: 0)  
		shopper_id (INT 10, default: 0)
		activity_id (INT 10, default: 0)  
		interface_id (INT 10, default: 0)
		event (TEXT, nullable, default: null)
		channel (TEXT, nullable, default: null)
		channel_id (INT 10, default: 0)  
		date (DATE, default: null)  
		time (DATE, default: null)  
		taxonomy (TEXT, default: null) 
		behavior (TEXT, default: null)  
		behavior_frequency (DOUBLE (15,8), default: 0)  
		artifact_id (INT 10, default: 0)  
		artifact_frequency (DOUBLE (15, 8), default: 0)  
		interaction_id (INT 10, default: 0)  
		interaction_frequency (DOUBLE (15,8), default: 0)
		value (BIGINT 20)
		author (INT 10, references id on 'users')
		created_at (TIMESTAMP)
		updated_at (TIMESTAMP)  
		
**Activities**  

	route: /activities
	Properties:
		id (INT 10, primary key, autoincrement)
		catalog_id (INT 10, references id on 'catalogs')
		type_id (INT 10, references id on 'types')
		name (VARCHAR 255)
		description (TEXT, nullable, default: null)
		link_to (INT 10, default: 0)
		link_from (INT 10, default: 0)
		created_at (TIMESTAMP)
		updated_at (TIMESTAMP)  
		
**PersonalPrices**

	route: /pprices
	Properties:
		id (INT 10, primary key, autoincrement)
		user_id (INT 10, references id on 'users')
		object_id (INT 10, references id on 'objects')
		personal_price (DOUBLE(12,2), nullable, default: null)
		created_at (TIMESTAMP)
		updated_at (TIMESTAMP) 
		
**Messages**

	route: /messages
	Properties:
		id (INT 10, primary key, autoincrement)
		type_id (INT 10, references id on 'types')
		message_thread (INT 10, default: 0)
		message_thread_id (INT 10, references id on 'messages', default: 0)
		sender (INT 10, references id on 'users')
		recipient (INT 10, references id on 'users')
		message (TEXT)
		image (VARCHAR 255, nullable, default: null)
		actstem (VARCHAR 255, nullable, default: null)
		count_trendup (INTEGER 10, default: 0)
		count_trenddown (INTEGER 10, default: 0)
		count_replies (INTEGER 10, default: 0)
		created_at (TIMESTAMP)
		updated_at (TIMESTAMP) 
		
**Invites**

	route: /invites
	Properties:
		id (INT 10, primary key, autoincrement)
		email (VARCHAR 255)
		accepted (ENUM [0, 1], default: 0)
		accepted_on (DATETIME, default: 0000-00-00 00:00:00)
		auhtor (INT 10, references id on 'users')
		created_at (TIMESTAMP)
		updated_at (TIMESTAMP) 
		
**Commissions**

	route: /commissions
	Properties:
		id (INT 10, primary key, autoincrement)
		user_id (INT 10, references id on 'users')
		catalog_id (INT 10, references id on 'catalogs')
		commission (DOUBLE (15,8))
		commission_accrued (DOUBLE (15,8))
		commission_rate (DOUBLE (15,8))
		product_sales (DOUBEL (15,8))
		created_at (TIMESTAMP)
		updated_at (TIMESTAMP) 
		
# Request Types

GET, POST, PUT, DELETE

# CSRF Protection

* Request type: GET will each time return the value of the "_token" parameter.  
* Request types: POST, PUT, DELETE requires "_token" parameter.

Each time an endpoint is requested with GET, every response will include the _token parameter. The client communicating with the API can consider this as the new token value, store it and send it back with every POST, PUT, DELETE request.

* Example 1: The client is requesting details of a catalog by sending GET request to: /catalogs/12. This will return the catalog object and a _token value. The client is updating the catalog details and is sending a PUT request to /catalogs/12. The client will have to send the _token value which was previosly requested with the GET.
* Example 2: To do an actual authentication, the client sends POST request to /auth/login. Before that request the client has to call /auth/login wih GET. This request will display the authentication status (Not logged in - in this case) and return a _token value. The client can now do the actual authenticating by POSTing to /auth/login along with the value of the _token.

# Authentication

Each API endpoint (except: /auth/login) is protected with authentication. The client must do a valid login by POSTing to /auth/login before reaching other endpoints. Login status can be aquired by sending a GET request to /auth/login endpoint. The login status will return a success or an error message - depending on the current authentication status. When session expires, each protected endpoint will return an error response stating that the current session is not valid eny more. In this case re-authentication is needed from client.  
With the authentication a "remember=1" parameter can be sent which will result an extended session.

* Note: every response from the API will return the current, authenticated "_ user _ id". If the user is not authenticated, the "_ user _ id" is null.

# UserGroups

* id: 1 - root (root@localhost)  
 > root authentication for no-limit access
* id: 2 - admin (admin@localhost)  
 > admin access for admin interface purposes
* id: 100 - generic (generic@localhost)  
 > generic access (for use with theme based catalogs)
* id: 200 - user  
 > normal users
 
### TODO HERE: retails and curators
		
# Endpoints  

# Endpoints "auth"

## /auth/login

Get status of user's auth status.

	URL: /auth/login  
	Type: GET  
	Parameters: -  
	Returns:  
		- response with type: success  
		- response with type: error

## /auth/login

Login user with email/password inputs. 
  
	URL: /auth/login  
	Type: POST  
	Parameters:  
		- email  
		- password
		- remember  
		- _token  
	Returns:  
		- response with type: success  
		- response with type: error

## /auth/logout  

Logout user.

	URL: /auth/logout  
	Type: GET  
	Parameters: -  
	Returns:  
		- response with type: success  
		- response with type: error  

# Endpoints "user"  

## /users

List user accounts.

	URL: /users
	Type: GET
	Parameters: -
	Returns:
		- response with type: result ([UserObjects])

## /users/{id}

Show one particular user.

	URL: /users/{id} 
	Type: GET  
	Parameters: id  
	Returns:  
		- response with type: result (UserObject)
		- response with type: error (user not found)  

## /users

Add a new user.

> Note: if a normal user does the addition, the new user will become it's curator. Every object, catalog, collection of the parent account will be editable to this curator account.  

> Note: adding new users with the admin account makes the newly added user a normal user.

> Note: the name, email and password are required fields. Passwords are required to be at least 5 characters long, and emails must be unique against the current userbase.  

> Note: tags are divided by ";" (tag1;tag2;tag3;...;tagn)

	URL: /users
	Type: POST
	Parameters: tags, name, email, password, image, profile_name, profile_description, api_paypal, api_loyalty, api_gift, chat, _token
	Returns:
		- response with type: success
		- response with type: error


## /users/{id}

Update user properties (users can only updated their own properties).  

> Note: not mandatory fields can be sent separately.  

> Note: tags are divided by ";" (tag1;tag2;tag3;...;tagn)

	URL: /users/{id} 
	Type: PUT  
	Parameters (URL): id
	Parameters (POST): tags, name, email, password, image, profile_name, profile_description, api_paypal, api_loyalty, api_gift, commission_rate_flag, chat, _token  
	Returns:  
		- response with type: success
		- response with type: error  

## /users/{id}/objects

List all objects belonging to the user (empty if none).

	URL: /users/{id}/objects  
	Type: GET  
	Parameters: id  
	Returns:  
		- response with type: result ([ObjectObjects])
		- response with type: error (user not found)

## /users/{id}/catalogs

List all catalogs belonging to the user (empty if none).

	URL: /users/{id}/catalogs  
	Type: GET  
	Parameters: id  
	Returns:  
		- response with type: result ([CatalogObjects])
		- response with type: error (user not found)  

## /users/{id}/collections

List all collections belonging to the user (empty if none).

	URL: /users/{id}/collections  
	Type: GET  
	Parameters: id  
	Returns:  
		- response with type: result ([CollectionObjects])
		- response with type: error (user not found)

## /users/{id}/comments

Display all comments made by the user.

	URL: /users/{id}/comments  
	Type: GET  
	Parameters: id  
	Returns:  
		- response with type: result ([CommentObjects])
		- response with type: error (user not found)  

## /users/{id}/likes

List all likes made by the user.

	URL: /users/{id}/likes  
	Type: GET
	Parameters: id
	Returns:  
		- response with type: result ([LikeObjects])
		- response with type: error (user not found)  

## /users/{id}/following

List all follows made by the user.

	URL: /users/{id}/following  
	Type: GET
	Parameters: id
	Returns:  
		- response with type: result ([FollowObjects])
		- response with type: error (user not found)  

## /users/{id}/feedbacks

List all feedbacks made by the user.

	URL: /users/{id}/feedbacks  
	Type: GET
	Parameters: id
	Returns:  
		- response with type: result ([FeedbackObjects])
		- response with type: error (user not found)  

## /users/{id}/follow

Follow a user.

	URL: /users/{id}/follow  
	Type: POST
	Parameters (URL): id
	Parameters (POST): _token
	Returns:  
		- response with type: success
		- response with type: error  

## /users/{id}/follows

List everyone who follows this user.

	URL: /users/{id}/follows  
	Type: GET
	Parameters: id
	Returns:  
		- response with type: result ([FollowObjects])
		- response with type: error (user not found)  

## /users/{id}/friend

Add user as a friend.

> Note: the friend request is sent from the current, authenticated user

> Note: the friend request is sent to the user referenced by the {id}

	URL: /users/{id}/friend
	Type: POST
	Parameters: id
	Returns:
		- response with type: success
		- response with type: error  

## /users/{id}/unfriend

Unfriend user.

> Note: with this enpoint the current, authenticated user will unfriend the user referenced by the {id}.

	URL: /users/{id}/unfriend
	Type: POST
	Parameters: id
	Returns:
		- response with type: success
		- response with type: error  

## /users/{id}/friends

List each one who is a friend of the user referenced by the {id}.

	URL: /users/{id}/friends  
	Type: GET
	Parameters: id
	Returns:  
		- response with type: result ([UserObjects])
		- response with type: error (user not found)  

## /users/{id}/messages/sent

List messages sent by this user.

	URL: /users/{id}/messages/sent
	Type: GET
	Parameters: id
	Returns:
		- response with type: result ([MessageObjects])
		- reposnse with type: error (user not found)  

## /users/{id}/messages/received

List messages received by this user.

	URL: /users/{id}/messages/received
	Type: GET
	Parameters: id
	Returns:
		- response with type: result ([MessageObjects])
		- reposnse with type: error (user not found)

## /users/{id}/invites/sent

List of invites this user have sent.

	URL: /users/{id}/invites/sent
	Type: GET
	Parameters: id
	Returns:
		- response with type: result ([InviteObjects])

## /search/users

Search users.  

	URL: /search/users
	Type: POST
	Parameters: term, _token
	Returns:
		- response with type: result ([UserObjects])

## /filter/users

Filter users.

	URL: /filter/users
	Type: POST
	Parameters: filter, operator, value, _token
	Returns:
		- response with type: result ([UserObjects])

* supported filters: [created _ at, updated _ at]

* supported operators: [=, <, >]  

## /users/preferences/{key}/get

Get value for a user preference.

	URL: /users/preferences/{key}/get
	Type: GET
	Parameters (URL): key  
	Returns:
		- response with type: result (UserPreferenceObject)  

* supported keys: [EmailYrCat, EmailFrCat, EmailComments, EmailLikes, EmailPrivateMessages, EmailMilestones, EmailYrReq, EmailFrReq, EmailReqComments, PushCom, PushLikes, PushFollows, PushPriMessages, PushFrCatalog, PushMilestones, PushReqCom, PushRequest, PushFillsAReq]  

## /users/preferences/all

Get all user's preferences.

	URL: /users/preferences/all
	Type: GET
	Parameters: -
	Returns:
		 - response with type: result ([UserPreferenceObjects])  

## /users/preferences/{key}/set  

Set a user preference.

	URL: /users/preferences/{key}/set
	Type: POST
	Parameters (URL): key
	Parameters (POST): value
	Returns:
		- response with type: success
		- response with type: error   

* supported keys: [EmailYrCat, EmailFrCat, EmailComments, EmailLikes, EmailPrivateMessages, EmailMilestones, EmailYrReq, EmailFrReq, EmailReqComments, PushCom, PushLikes, PushFollows, PushPriMessages, PushFrCatalog, PushMilestones, PushReqCom, PushRequest, PushFillsAReq]

* supported values: [0, 1]  
	
##	/users/{id}/commissions/rate

Set commission rate for a specified user.

> Note: this endpoint is only reacable for 'admin' users.

	URL: /users/{id}/commissions/rate
	Type: POST
	Parameters (URL): id
	Parameters (POST): rate, _token
	Returns:
		- response with type: success
		- response with type: error

## /users/{id}/commissions/exchange

Set exchange rate for user.

> Note: this endpoint is only reachable for 'admin' users.

	URL: /users/{id}/commissions/exchange
	Type: POST
	Parameters (URL): id
	Parameters (POST): exchange, _token
	Returns:
		- response with type: success
		- response with type: error

## /users/{id}/commissions/pay

Pay the user. This will substract the given amount from user's commissions. The return value will depend on the current commission_exchage attribute.

> Note: this endpoint is only reachable for 'admin' users.

	URL: /users/{id}/commissions/pay
	Type: POST
	Parameters (URL): id
	Parameters (POST): amount, _token
	Returns:
		- response with type: result (Amount, DoubleVal)
		- response with type: error


# Endpoints "object"  

## /objects

List all objects.

	URL: /objects  
	Type: GET  
	Parameters: -  
	Returns:  
		- response with type: result ([ObjectObjects])  

## /objects/{id}

Show one particular object.

	URL: /objects/{id} 
	Type: GET  
	Parameters: id  
	Returns:  
		- response with type: result (ObjectObject)
		- response with type: error (object not found)  

## /objects

Add a new object.  

> Note: the "type" property can be a numeric id or string name. If type name is given, the API will try to locate that within the existing types and assign the id accordingly. If the given type name doesn't exists in the database, it will be created and the id assigned accordingly.  

> Note: the "category" property can be a numeric id or string name. If category name is given, the API will try to locate that within the existing categories and assign the id accordingly. If the given category name doesn't exists in the database, it will be created and the id assigned accordingly.

> Note: tags are divided by ";" (tag1;tag2;tag3;...;tagn)

	URL: /objects 
	Type: POST  
	Parameters: catalog_id, category, type, tags, name, description, url, image, weight, retail_price, sale_price, offer_value, offer_url, offer_description, offer_start, offer_stop, prod_detail_url, layout, position, competitor_flag, curated, _token  
	Returns:  
		- response with type: success
		- response with type: error

## /objects/{id}

Update object properties (users can only updated objects owned by them).  

> Note: not mandatory fields can be sent separately. For example to change under which catalog an object belongs, it is enough to send only the catalog_id value (and of course the _token).  

> Note: the "type" property can be a numeric id or string name. If type name is given, the API will try to locate that within the existing types and assign the id accordingly. If the given type name doesn't exists in the database, it will be created and the id assigned accordingly.

> Note: the "category" property can be a numeric id or string name. If category name is given, the API will try to locate that within the existing categories and assign the id accordingly. If the given category name doesn't exists in the database, it will be created and the id assigned accordingly.

> Note: tags are divided by ";" (tag1;tag2;tag3;...;tagn)

	URL: /objects/{id} 
	Type: PUT  
	Parameters (URL): id
	Parameters (POST): catalog_id, category, type, tags, name, description, url, image, weight, retail_price, sale_price, offer_value, offer_url, offer_description, offer_start, offer_stop, prod_detail_url, layout, position, competitor_flag, curated, _token  
	Returns:  
		- response with type: success
		- response with type: error  

## /objects/{id}

Delete an object (a user can only delete his objects)

	URL: /objects/{id}
	Type: DELETE
	Parameters: id
	Returns:
		- response with type: success
		- response with type: error (object not found)

## /objects/{id}/catalog

Show in which catalog the current object resides (empty if none).

	URL: /objects/{id}/catalog  
	Type: GET  
	Parameters: id  
	Returns:  
		- response with type: result (CatalogObject)
		- response with type: error (object not found)

## /objects/{id}/comments

Display all comments for an object.

	URL: /objects/{id}/comments  
	Type: GET  
	Parameters: id  
	Returns:  
		- response with type: result ([CommentObjects])
		- response with type: error (object not found)  

## /objects/{id}/comment

Comment on an object.

	URL: /objects/{id}/comment  
	Type: POST
	Parameters (URL): id
	Parameters (POST): text, _token
	Returns:  
		- response with type: success
		- response with type: error  

## /objects/{id}/comments

List all comments for an object.

	URL: /objects/{id}/comments  
	Type: GET
	Parameters: id
	Returns:  
		- response with type: result ([CommentObjects])
		- response with type: error (object not found) 

## /objects/{id}/like

Like an object.

	URL: /objects/{id}/like  
	Type: POST
	Parameters (URL): id
	Parameters (POST): _token
	Returns:  
		- response with type: success
		- response with type: error  

## /objects/{id}/likes

List all likes for an object.

	URL: /objects/{id}/likes  
	Type: GET
	Parameters: id
	Returns:  
		- response with type: result ([LikeObjects])
		- response with type: error (object not found)  

## /objects/{id}/follow

Follow an object.

	URL: /objects/{id}/follow  
	Type: POST
	Parameters (URL): id
	Parameters (POST): _token
	Returns:  
		- response with type: success
		- response with type: error  

## /objects/{id}/follows

List all follows for an object.

	URL: /objects/{id}/follows  
	Type: GET
	Parameters: id
	Returns:  
		- response with type: result ([FollowObjects])
		- response with type: error (object not found) 

## /objects/{id}/recommend

Recommend an object.

	URL: /objects/{id}/recommend  
	Type: POST
	Parameters (URL): id
	Parameters (POST): _token
	Returns:  
		- response with type: success
		- response with type: error  

## /objects/{id}/recommendations

List all recommendations for an object.

	URL: /objects/{id}/recommendations  
	Type: GET
	Parameters: id
	Returns:  
		- response with type: result ([RecommendationObjects])
		- response with type: error (object not found)  

## /objects/{id}/feedback

Feedback on an object.

	URL: /objects/{id}/feedback  
	Type: POST
	Parameters (URL): id
	Parameters (POST): product_id, offer_id, shopper_id, activity_id, interface_id, event, channel, channel_id, date, time, taxonomy, behavior, behavior_frequency, artifact_id, artifact_frequency, interaction_id, interaction_frequency, value, _token
	Returns:  
		- response with type: success
		- response with type: error  

## /objects/{id}/feedbacks

List all feedbacks for an object.

	URL: /objects/{id}/feedbacks  
	Type: GET
	Parameters: id
	Returns:  
		- response with type: result ([FeedbackObjects])
		- response with type: error (object not found) 

## /deleted/objects

View objects deleted by the user

	URL: /deleted/objects
	Type: GET
	Returns:
		- response with type: result ([ObjectObjects])

## /search/objects

Search objects by name and description  

	URL: /search/objects
	Type: POST
	Parameters: term, _token
	Returns:
		- response with type: result ([ObjectObjects])

## /filter/objects

Filter objects  

	URL: /filter/objects
	Type: POST
	Parameters: filter, operator, value, _token
	Returns:
		- response with type: result ([ObjectObjects])

* supported filters: [catalog _ id, category _ id, type _ id, retail _ price, sale _ price, layout, position, competitor _ flag, recomended, curated, author, created _ at, updated _ at]

* supported operators: [=, <, >]

	
# Endpoints "catalog"  

## /catalogs

List all catalogs.

	URL: /catalogs  
	Type: GET  
	Parameters: -  
	Returns:  
		- response with type: result ([CatalogObjects])  

## /catalogs/{id}

Show one particular catalog.

	URL: /catalogs/{id} 
	Type: GET  
	Parameters: id  
	Returns:  
		- response with type: result (CatalogObject)
		- response with type: error (catalog not found)  

## /catalogs

Add a new catalog.

> Note: the "type" property can be a numeric id or string name. If type name is given, the API will try to locate that within the existing types and assign the id accordingly. If the given type name doesn't exists in the database, it will be created and the id assigned accordingly.

> Note: the "category" property can be a numeric id or string name. If category name is given, the API will try to locate that within the existing categories and assign the id accordingly. If the given category name doesn't exists in the database, it will be created and the id assigned accordingly.

> Note: tags are divided by ";" (tag1;tag2;tag3;...;tagn)

	URL: /catalogs 
	Type: POST  
	Parameters: category, type, tags, name, title, description, image, layout, position, publish, trending, popular, earning_trend, earning_total, earning_place, earning_cat_place, earning_potential, _token  
	Returns:  
		- response with type: success
		- response with type: error

## /catalogs/{id}

Update catalog properties (users can only update catalogs owned by them).  

> Note: the "type" property can be a numeric id or string name. If type name is given, the API will try to locate that within the existing types and assign the id accordingly. If the given type name doesn't exists in the database, it will be created and the id assigned accordingly.

> Note: the "category" property can be a numeric id or string name. If category name is given, the API will try to locate that within the existing categories and assign the id accordingly. If the given category name doesn't exists in the database, it will be created and the id assigned accordingly.

> Note: tags are divided by ";" (tag1;tag2;tag3;...;tagn)

	URL: /catalogs/{id} 
	Type: PUT  
	Parameters (URL): id
	Parameters (POST): category, type, tags, name, title, description, image, layout, position, publish, trending, popular, earning_trend, earning_total, earning_place, earning_cat_place, earning_potential, _token 
	Returns:  
		- response with type: success
		- response with type: error  

## /catalogs/{id}

Delete a catalog (a user can only delete his catalogs)

	URL: /catalogs/{id}
	Type: DELETE
	Parameters: id
	Returns:
		- response with type: success
		- response with type: error (catalog not found)

## /catalogs/{id}/objects

Display all objects residing in the catalog (empty if none).

	URL: /catalogs/{id}/objects  
	Type: GET  
	Parameters: id  
	Returns:  
		- response with type: result ([ObjectObjects])
		- response with type: error (catalog not found)  

## /catalogs/{id}/products

Display all products residing in the catalog (empty if none).

	URL: /catalogs/{id}/products
	Type: GET
	Parameters: id
	Returns:
		- response with type: result ([ObjectObjects])
		- response with type: error (catalog not found)

## /catalogs/{id}/content  

Display catalog's content. Catalog info and all objects residing in the catalog (empty if none).

	URL: /catalogs/{id}/contents  
	Type: GET  
	Parameters: id  
	Returns:  
		- response with type: result (CatalogObject, [ObjectObjects])
		- response with type: error (catalog not found)  

## /catalogs/{id}/comment

Comment on a catalog.

	URL: /catalogs/{id}/comment  
	Type: POST
	Parameters (URL): id
	Parameters (POST): text, _token
	Returns:  
		- response with type: success
		- response with type: error  

## /catalogs/{id}/comments

List all comments for a catalog.

	URL: /catalog/{id}/comments  
	Type: GET
	Parameters: id
	Returns:  
		- response with type: result ([CommentObjects])
		- response with type: error (object not found) 

## /catalogs/{id}/like

Like a catalog.

	URL: /catalogs/{id}/like  
	Type: POST
	Parameters (URL): id
	Parameters (POST): _token
	Returns:  
		- response with type: success
		- response with type: error  

## /catalogs/{id}/likes

List all likes for a catalog.

	URL: /catalog/{id}/likes  
	Type: GET
	Parameters: id
	Returns:  
		- response with type: result ([LikeObjects])
		- response with type: error (object not found) 

## /catalogs/{id}/follow

Follow a catalog.

	URL: /catalogs/{id}/follow  
	Type: POST
	Parameters (URL): id
	Parameters (POST): _token
	Returns:  
		- response with type: success
		- response with type: error  

## /catalogs/{id}/follows

List all follows for a catalog.

	URL: /catalog/{id}/follows  
	Type: GET
	Parameters: id
	Returns:  
		- response with type: result ([FollowObjects])
		- response with type: error (object not found)  

## /catalogs/{id}/recommend

Recommend a catalog.

	URL: /catalogs/{id}/recommend  
	Type: POST
	Parameters (URL): id
	Parameters (POST): _token
	Returns:  
		- response with type: success
		- response with type: error  

## /catalogs/{id}/recommendations

List all recommendations for a catalog.

	URL: /catalogs/{id}/recommendations  
	Type: GET
	Parameters: id
	Returns:  
		- response with type: result ([RecommendationObjects])
		- response with type: error (catalog not found)  

## /catalogs/{id}/feedback

Feedback on a catalogs.

	URL: /catalogs/{id}/feedback  
	Type: POST
	Parameters (URL): id
	Parameters (POST): product_id, offer_id, shopper_id, activity_id, interface_id, event, channel, channel_id, date, time, taxonomy, behavior, behavior_frequency, artifact_id, artifact_frequency, interaction_id, interaction_frequency, value, _token
	Returns:  
		- response with type: success
		- response with type: error  

## /catalogs/{id}/feedbacks

List all feedbacks for a catalog.

	URL: /catalogs/{id}/feedbacks  
	Type: GET
	Parameters: id
	Returns:  
		- response with type: result ([FeedbackObjects])
		- response with type: error (catalog not found) 

## /catalogs/{id}/activities

List all activities for a catalog.

	URL: /catalogs/{id}/actvities
	Type: GET
	Parameters: id
	Returns:
		- response with type: result ([ActivityObjects])
		- response with type: error (catalog not found)	
		
## /deleted/catalogs

View catalogs deleted by the user

	URL: /deleted/catalogs
	Type: GET
	Returns:
		- response with type: result ([CatalogObjects])

## /search/catalogs

Search catalogs by name, title and description

	URL: /search/catalogs
	Type: POST
	Parameters: term, _token
	Returns:
		- response with type: result ([CatalogObjects])

## /filter/catalogs

Filter catalogs  

	URL: /filter/catalogs
	Type: POST
	Parameters: filter, operator, value, _token
	Returns:
		- response with type: result ([CatalogObjects])

* supported filters: [category _ id, type _ id, author, created _ at, updated _ at]

* supported operators: [=, <, >]

# Endpoints "collection"  

## /collections

List all collections for the current, authenticated user.

	URL: /collections  
	Type: GET  
	Parameters: -  
	Returns:  
		- response with type: result ([[CollectionObjects]])  

## /collections/{collection_id}

Show one particular collection belonging to the current, authenticated user.

	URL: /collections/{collection_id} 
	Type: GET  
	Parameters: collection_id  
	Returns:  
		- response with type: result ([CollectionObject])
		- response with type: error (collection not found)  

## /collections/{collection_id}/objects

List all objects belonging to this collection.  

> Note: only reaches to collections belonging to the current, authenticated user.

	URL: /collections/{collection_id}/objects 
	Type: GET  
	Parameters: collection_id  
	Returns:  
		- response with type: result ([ObjectObjects])
		- response with type: error (collection not found)

## /collections/{collection_id}/catalogs

List all catalogs belonging to this collection.  

> Note: only reaches to collections belonging to the current, authenticated user.

	URL: /collections/{collection_id}/catalogs 
	Type: GET  
	Parameters: collection_id  
	Returns:  
		- response with type: result ([CatalogObjects])
		- response with type: error (collection not found)  

## /collections

Add a new collection.

> Note: when adding a new collection the first catalog's or object's id (foreign_id) must be sent with the POST parameters. Additional catalogs, objects to this collection can be added via the /collection/{id}/add/... endpoints.

	URL: /collections 
	Type: POST  
	Parameters: foreign_id, foreign_type, _token  
	Returns:  
		- response with type: success
		- response with type: error  

* supported foreign types: ['object', 'catalog']  

## /collections/{collection_id}/add/object

Add an object to a specified collection.

	URL: /collections/{collection_id}/add/object  
	Type: POST  
	Parameters (URL): collection_id
	Parameters (POST): object_id, _token  
	Returns:  
		- response with type: success
		- response with type: error
		- response with type: error (collection not found)

## /collections/{collection_id}/add/catalog

Add a catalog to a specified collection.

	URL: /collections/{collection_id}/add/catalog  
	Type: POST  
	Parameters (URL): collection_id
	Parameters (POST): catalog_id, _token  
	Returns:  
		- response with type: success
		- response with type: error
		- response with type: error (collection not found)  

## /collections/{collection_id}/remove/object

Remove an object from a specified collection.

	URL: /collections/{collection_id}/remove/object 
	Type: POST  
	Parameters (URL): collection_id
	Parameters (POST): object_id, _token  
	Returns:  
		- response with type: success
		- response with type: error
		- response with type: error (collection not found) 

## /collections/{collection_id}/remove/catalog

Remove a catalog from a specified collection.

	URL: /collections/{collection_id}/remove/catalog 
	Type: POST  
	Parameters (URL): collection_id
	Parameters (POST): catalog_id, _token  
	Returns:  
		- response with type: success
		- response with type: error
		- response with type: error (collection not found)  

## /collections/{id}

Delete a collection (a user can only delete his collections)

	URL: /collections/{id}
	Type: DELETE
	Parameters: id
	Returns:
		- response with type: success
		- response with type: error (collection not found)

## /deleted/collections

View collections deleted by the user

	URL: /deleted/collections
	Type: GET
	Returns:
		- response with type: result ([CollectionObjects])  

# Endpoints "generic-collection"  

## /generic-collections

List all generic collections.

	URL: /generic-collections  
	Type: GET  
	Parameters: -  
	Returns:  
		- response with type: result ([[GenericCollectionObjects]])  

## /generic-collections/{collection_id}

Show one particular generic collection.

	URL: /generic-collections/{collection_id} 
	Type: GET  
	Parameters: collection_id  
	Returns:  
		- response with type: result ([GenericCollectionObject])
		- response with type: error (generic collection not found)  

## /generic-collections/{collection_id}/objects

List all objects belonging to this generic collection.  

	URL: /generic-collections/{collection_id}/objects 
	Type: GET  
	Parameters: collection_id  
	Returns:  
		- response with type: result ([ObjectObjects])
		- response with type: error (generic collection not found)

## /generic-collections/{collection_id}/catalogs

List all catalogs belonging to this generic collection.  

	URL: /generic-collections/{collection_id}/catalogs 
	Type: GET  
	Parameters: collection_id  
	Returns:  
		- response with type: result ([CatalogObjects])
		- response with type: error (generic collection not found)  

## /generic-collections

Add a new generic collection.

> Note: only admin users can add new generic collection.

> Note: when adding a new generic collection the first catalog's or object's id (foreign_id) must be sent with the POST parameters. Additional catalogs, objects to this collection can be added via the /generic-collection/{id}/add/... endpoints.

	URL: /generic-collections 
	Type: POST  
	Parameters: foreign_id, foreign_type, _token  
	Returns:  
		- response with type: success
		- response with type: error  

* supported foreign types: ['object', 'catalog']  

## /generic-collections/{id}

Delete a generic collection.

> Note: only admin users can delete a generic collection.

	URL: /generic-collections/{id}
	Type: DELETE
	Parameters: id
	Returns:
		- response with type: success
		- response with type: error (generic collection not found)

## /generic-collections/{collection_id}/add/object

Add an object to a specified generic collection.

> Note: only admin users can add objects to generic collections.

	URL: /generic-collections/{collection_id}/add/object  
	Type: POST  
	Parameters (URL): collection_id
	Parameters (POST): object_id, _token  
	Returns:  
		- response with type: success
		- response with type: error
		- response with type: error (generic collection not found)

## /generic-collections/{collection_id}/add/catalog

Add a catalog to a specified generic collection.

> Note: only admin users can add catalogs to generic collections.

	URL: /generic-collections/{collection_id}/add/catalog  
	Type: POST  
	Parameters (URL): collection_id
	Parameters (POST): catalog_id, _token  
	Returns:  
		- response with type: success
		- response with type: error
		- response with type: error (generic collection not found)  

## /generic-collections/{collection_id}/remove/object

Remove an object from a specified generic collection.

> Note: only admin users can remove objects from generic collections.

	URL: /generic-collections/{collection_id}/remove/object 
	Type: POST  
	Parameters (URL): collection_id
	Parameters (POST): object_id, _token  
	Returns:  
		- response with type: success
		- response with type: error
		- response with type: error (collection not found) 

## /generic-collections/{collection_id}/remove/catalog

Remove a catalog from a specified generic collection.

> Note: only admin users can remove catalogs from generic collections.

	URL: /collections/{collection_id}/remove/catalog 
	Type: POST  
	Parameters (URL): collection_id
	Parameters (POST): catalog_id, _token  
	Returns:  
		- response with type: success
		- response with type: error
		- response with type: error (collection not found)  

## /generic-collections/{id}

Delete a generic collection.

> Note: only admin users can delete a generic collection.

	URL: /generic-collections/{id}
	Type: DELETE
	Parameters: id
	Returns:
		- response with type: success
		- response with type: error (generic collection not found) 

## /deleted/generic-collections

View deleted generic collections.  

> Note: only admin users can view deleted generic collections.

	URL: /deleted/generic-collections
	Type: GET
	Returns:
		- response with type: result ([GenericCollectionObjects])

# Endpoints "route"  

## /routes

List all routes for the current, authenticated user.

	URL: /routes  
	Type: GET  
	Parameters: -  
	Returns:  
		- response with type: result ([RouteObjects])  

## /routes/{id}

Show one particular route.

	URL: /routes/{id} 
	Type: GET  
	Parameters: id  
	Returns:  
		- response with type: result (RouteObject)
		- response with type: error (route not found)  

## /routes

Add a new route.

> Note: object_ids are divided by ";" (13;32;43;...;n)

	URL: /routes 
	Type: POST  
	Parameters: name, description, data, object_ids, _token  
	Returns:  
		- response with type: success
		- response with type: error

## /routes/{id}

Update route properties (users can only update routes owned by them).  

> Note: object_ids are divided by ";" (19;24;33;...;n)

	URL: /routes/{id} 
	Type: PUT  
	Parameters (URL): id
	Parameters (POST): name, description, data, object_ids, _token
	Returns:  
		- response with type: success
		- response with type: error  

## /routes/{id}

Delete a route (a user can only delete his routes)

	URL: /route/{id}
	Type: DELETE
	Parameters: id
	Returns:
		- response with type: success
		- response with type: error (route not found)

## /route/{id}/objects

Display all objects belonging to this route (empty if none).

	URL: /routes/{id}/objects  
	Type: GET  
	Parameters: id  
	Returns:  
		- response with type: result ([ObjectObjects])
		- response with type: error (route not found)
		
## /deleted/routes

View routes deleted by the user

	URL: /deleted/routes
	Type: GET
	Returns:
		- response with type: result ([RouteObjects])

# Endpoints "category"  

## /categories

List all categories.

	URL: /categories  
	Type: GET  
	Parameters: -  
	Returns:  
		- response with type: result ([CategoryObjects])  

## /categories/{id}

Show one particular category.

	URL: /categories/{id} 
	Type: GET  
	Parameters: id  
	Returns:  
		- response with type: result (CategoryObject)
		- response with type: error (category not found)  

## /categories/{id}/objects

Display all objects belonging to this category (empty if none).

	URL: /categories/{id}/objects  
	Type: GET  
	Parameters: id  
	Returns:  
		- response with type: result ([ObjectObjects])
		- response with type: error (category not found)  

## /categories/{id}/catalogs

Display all catalogs belonging to this category (empty if none).

	URL: /categories/{id}/catalogs  
	Type: GET  
	Parameters: id  
	Returns:  
		- response with type: result ([CatalogObjects])
		- response with type: error (category not found)  

# Endpoints "type"  

## /types

List all types.

	URL: /types  
	Type: GET  
	Parameters: -  
	Returns:  
		- response with type: result ([TypeObjects])  

## /types/{id}

Show one particular type.

	URL: /types/{id} 
	Type: GET  
	Parameters: id  
	Returns:  
		- response with type: result (TypeObject)
		- response with type: error (type not found)  

## /types/{id}/objects

Display all objects belonging to this type (empty if none).

	URL: /types/{id}/objects  
	Type: GET  
	Parameters: id  
	Returns:  
		- response with type: result ([ObjectObjects])
		- response with type: error (type not found)  

## /types/{id}/catalogs

Display all catalogs belonging to this type (empty if none).

	URL: /types/{id}/catalogs  
	Type: GET  
	Parameters: id  
	Returns:  
		- response with type: result ([CatalogObjects])
		- response with type: error (type not found)

# Endpoints "comment"   

## /comments/{id}

Show one particular comment.

	URL: /comments/{id} 
	Type: GET  
	Parameters: id  
	Returns:  
		- response with type: result (CommentObject)
		- response with type: error (comment not found)  

## /comments/{id}

Update comment properties (users can only updated comments owned by them).

	URL: /comments/{id} 
	Type: PUT  
	Parameters (URL): id
	Parameters (POST): text, _token 
	Returns:  
		- response with type: success
		- response with type: error

# Endpoints "like"   

## /likes/{id}

Delete a like (unlike).

	URL: /likes/{id} 
	Type: DELETE  
	Parameters (URL): id
	Parameters (POST): _token  
	Returns:  
		- response with type: success
		- response with type: error  

# Endpoints "follow"   

## /follows/{id}

Delete a follow (unfollow).

	URL: /follows/{id} 
	Type: DELETE  
	Parameters (URL): id
	Parameters (POST): _token  
	Returns:  
		- response with type: success
		- response with type: error  

# Endpoints "friendship"  

## /friends/requests

List pending friend request for the current, authenticated user. 

	URL: /friends/requests
	Type: GET
	Parameters: -
	Returns:
		- response with type: result (CommentObject)

## /friends/{id}/accept

Accept a friend request.

> Note: in this case, the {id} is refering to the friend request ID, not the user ID. The friend request ID can be obtained by using the previous endpoint (/friends/requests)

	URL: /friends/{id}/accept
	Type: POST  
	Parameters (URL): id
	Parameters (POST): _token  
	Returns:  
		- response with type: success
		- response with type: error  

# Endpoints "activity"  

## /activities/{id}

Show one particular activity.

	URL: /activities/{id} 
	Type: GET  
	Parameters: id  
	Returns:  
		- response with type: result (ActivityObject)
		- response with type: error (activity not found)  

## /activities

Add a new activity.

> Note: the "type" property can be a numeric id or string name. If type name is given, the API will try to locate that within the existing types and assign the id accordingly. If the given type name doesn't exists in the database, it will be created and the id assigned accordingly.

	URL: /activities 
	Type: POST  
	Parameters: catalog_id, type, name, description, link_to, link_from, _token  
	Returns:  
		- response with type: success
		- response with type: error

## /activities/{id}

Update activity properties.  

> Note: the "type" property can be a numeric id or string name. If type name is given, the API will try to locate that within the existing types and assign the id accordingly. If the given type name doesn't exists in the database, it will be created and the id assigned accordingly.

	URL: /activities/{id} 
	Type: PUT  
	Parameters (URL): id
	Parameters (POST): catalog, type, name, description, link_to, link_from, _token 
	Returns:  
		- response with type: success
		- response with type: error  

## /search/activities

Search activities by name and description.

	URL: /search/activities
	Type: POST
	Parameters: term, _token
	Returns:
		- response with type: result ([ActivityObjects])

## /filter/activities

Filter activities.  

	URL: /filter/activities
	Type: POST
	Parameters: filter, operator, value, _token
	Returns:
		- response with type: result ([ActivityObjects])

* supported filters: [catalog _ id, type _ id, created _ at, updated _ at]

* supported operators: [=, <, >]  

# Endpoints "pprice"   

## /pprices

Add a new personal price (authenticated user can only add personal prices to object where he is the owner).

	URL: /pprices 
	Type: POST  
	Parameters: user_id, object_id, personal_price, _token  
	Returns:  
		- response with type: success
		- response with type: error

## /pprices/{id}

Update personal price (authenticated user can only update personal price refering to his object).

	URL: /pprices/{id} 
	Type: PUT  
	Parameters (URL): id
	Parameters (POST): user_id, object_id, personal_price, _token 
	Returns:  
		- response with type: success
		- response with type: error  

## /pprices/{id}

Delete personal price (authenticated user can only delete personal price refering to his object).

	URL: /pprices/{id} 
	Type: DELETE  
	Parameters (URL): id
	Parameters (POST): _token 
	Returns:  
		- response with type: success
		- response with type: error 

# Endpoints "message"  

## /messages/{id}

Show one particular message.

	URL: /messages/{id} 
	Type: GET  
	Parameters: id  
	Returns:  
		- response with type: result (MessageObject)
		- response with type: error (message not found)  

## /messages

Send a new message. The sender is always the current, authenticated user - while the recepient ID is specified in the request (recipient parameter).  

> Note: the "type" property can be a numeric id or string name. If type name is given, the API will try to locate that within the existing types and assign the id accordingly. If the given type name doesn't exists in the database, it will be created and the id assigned accordingly.

	URL: /messages 
	Type: POST  
	Parameters: type, recipient, message, image, actstem, _token 
	Returns:  
		- response with type: success
		- response with type: error

## /messages/{id}/reply

Reply to a message. The sender is always the current, authenticated user - while the recepient ID is specified by the original message. 

	URL: /messages/{id}/reply
	Type: POST  
	Parameters (URL): id
	Parameters (POST): type, message, image, actstem, _token 
	Returns:  
		- response with type: success
		- response with type: error  

## /messages/from/follows

This endpoint will return all the messages sent by the users who the current, authenticated user is following.

	URL: /messages/from/follows 
	Type: GET   
	Parameters: -
	Returns:  
		- response with type: result (MessageObject)  

# Endpoints "invites"  

## /invites

Send a new invite. The sender is always the current, authenticated user - while the recepient ID is specified in the request (email parameter).  

	URL: /invites 
	Type: POST  
	Parameters: email, _token 
	Returns:  
		- response with type: success
		- response with type: error


# Endpoints "commission"  

> Note: these routes are only accessible to 'admin' users.

## /commissions

List all commissions.

	URL: /commissions  
	Type: GET  
	Parameters: -  
	Returns:  
		- response with type: result ([CommissionObjects])  

## /commissions/{id}

Show one particular commission.

	URL: /commissions/{id} 
	Type: GET  
	Parameters: id  
	Returns:  
		- response with type: result (CommissionObject)
		- response with type: error (commission not found)  

## /comissions

Add a new commission.

> Note: must contain a valid user and catalog ID. Commission will be automaticaly added to the specified user. Commission rate is defined in the user attributes.

	URL: /commissions
	Type: POST
	Parameters: user_id, catalog_id, product_sales, _token
		- response with type: success
		- response with type: error

## /filter/commissions

Filter commissions  

	URL: /filter/commissions
	Type: POST
	Parameters: filter, operator, value, _token
	Returns:
		- response with type: result ([CommissionObjects])

* supported filters: [user _ id, catalog _ id, created _ at]

* supported operators: [=, <, >]



# Responses

All responses are JSON encoded strings. Each response will return the "_token" value to use with CSRF protection.

## Response type: success

	{
		"type":"success",
		"message":"Some success message",
		"_user_id": 11,
		"_token":"IFMJy3kdsaReScNLLrNuNNixT59A6aJ3ghHAgeuL"
	}

## Response type: error

	{
		"type":"error",
		"message":"Some error message",
		"_user_id": 13,
		"_token":"IFMJy3kdsaReScNLLrNuNNixT59A6aJ3ghHAgeuL"
	}

## Response type: result

With singular content:  

	{
		"type":"result",
		"content":13,
		"_user_id": 23,
		"_token":"IFMJy3kdsaReScNLLrNuNNixT59A6aJ3ghHAgeuL"
	}

With object content:  

	{
		"type":"result",
		"content":
			{
				"id":12,
				"collection_id":1,
				"name":"et qui dolores",
				"title":"aut ipsum harum",
				"likes":43,
				"comments":2,
				"follows":15,
				"author":133
				"created_at":"2015-11-19 13:14:43",
				"updated_at":"2015-11-19 20:40:35"
			},
		"_user_id": 14,
		"_token":"IFMJy3kdsaReScNLLrNuNNixT59A6aJ3ghHAgeuL"
	}  
	
With array content: 

	{
		"type":"result",
		"content":
			[
				{
					"id":12,
					"collection_id":1,
					"name":"et qui dolores",
					"title":"aut ipsum harum",
					"likes":43,
					"comments":2,
					"follows":15,
					"author":133
					"created_at":"2015-11-19 13:14:43",
					"updated_at":"2015-11-19 20:40:35"
				},
				{
					"id":221,
					"collection_id":1,
					"name":"et qui dolores",
					"title":"aut ipsum harum",
					"likes":2,
					"comments":2,
					"follows":1,
					"author":143
					"created_at":"2015-11-19 13:14:43",
					"updated_at":"2015-11-19 20:40:35"
				},
				{
					"id":543,
					"collection_id":1,
					"name":"et qui dolores",
					"title":"aut ipsum harum",
					"likes":155,
					"comments":45,
					"follows":99,
					"author":12
					"created_at":"2015-11-19 13:14:43",
					"updated_at":"2015-11-19 20:40:35"
				},
				{
					"id":121,
					"collection_id":1,
					"name":"et qui dolores",
					"title":"aut ipsum harum",
					"likes":3,
					"comments":2,
					"follows":1,
					"author":13
					"created_at":"2015-11-19 13:14:43",
					"updated_at":"2015-11-19 20:40:35"
				},
				{
					"id":212,
					"collection_id":1,
					"name":"et qui dolores",
					"title":"aut ipsum harum",
					"likes":3,
					"comments":2,
					"follows":5,
					"author":33
					"created_at":"2015-11-19 13:14:43",
					"updated_at":"2015-11-19 20:40:35"
				}
			],
		"_user_id": 28,
		"_token":"IFMJy3kdsaReScNLLrNuNNixT59A6aJ3ghHAgeuL"
	}

## Pagination

Index listings are returning results paginated. Default is to return 10 results per one page. This behavior can be changed by sending the 'pp' parameter. The 'page' parameter can be used to move between pages.

Sample pagination:

		/objects
		/objects?page=1
		/objects?page=2
		....
		/objects?page=n
		
		OR
		
		/catalogs
		/catalogs?page=1
		/catalogs?page=2
		....
		/catalogs?page=n  
		
Sample setting the per page:

		/objects?pp=20
		/objects?page=1&pp=20
		/objects?page=2&pp=20
		....
		/objects?page=n&pp=20
		
		OR
		
		/catalogs?pp=30
		/catalogs?page=1&pp=30
		/catalogs?page=2&pp=30
		....
		/catalogs?page=n&pp=30 

### Routes where the pagination is applied

		/objects
		/search/objects
		/filter/objects
		/deleted/objects
		
		/catalogs
		/search/catalogs
		/filter/catalogs
		/deleted/catalogs
		
		/collections
		/deleted/collections

## -