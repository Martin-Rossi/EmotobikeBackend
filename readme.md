## Catalog API

# Models

**User**  

	route: /users  
	Properties:
		id (INT 10 - primary key, autoincrement)
		name (VARCHAR 255)
		email (VARCHAR 255, unique)
		password (VARCHAR 60 - bcrypted)
		remember_token (VARCHAR 100)
		created_at (TIMESTAMP)
		updated_at (TIMESTAMP)
		
**Object**  

	route: /objects
	Properties:
		id (INT 10 - primary key, autoincrement)
		catalog_id (INT 10 - references id on 'catalogs', default: 0)
		category_id (INT 10 - references id on 'categories', default: 0)
		type_id (INT 10 - references id on 'types', default: 0)
		name (VARCHAR 255)
		description (TEXT, nullable, default: null)
		url (VARCHAR 255, nullable, default: null)
		retail_price (DOUBLE(12,2), nullable, default: null)
		sale_price (DOUBLE(12,2), nullable, default: null)
		count_likes (INT 10, default: 0)
		count_comments (INT 10, default: 0)
		count_follows (INT 10, default: 0)
		competitor_flag (ENUM[0,1], default: 0)
		recomended (ENUM[0,1], default: 0)
		curated (ENUM[0,1], default: 0)
		author (INT 10, references id on 'users')
		created_at (TIMESTAMP)
		updated_at (TIMESTAMP)
		
**Catalog**  

	route: /catalogs
	Properties:
		id (INT 10 - primary key, autoincrement)
		name (VARCHAR 255)
		title (VARCHAR 255)
		count_likes (INT 10, default: 0)
		count_comments (INT 10, default: 0)
		count_follows (INT 10, default: 0)
		author (INT 10, references id on 'users')
		created_at (TIMESTAMP)
		updated_at (TIMESTAMP)
		
**Comments**  

	route: /comments
	Properties:
		id (INT 10 - primary key, autoincrement)
		foreign_id (INT 10)
		foreign_type (ENUM['object','catalog'])
		text (TEXT)
		author (INT 10, references id on 'users')
		created_at (TIMESTAMP)
		updated_at (TIMESTAMP)  
		
**Likes**  

	route: /likes
	Properties:
		id (INT 10 - primary key, autoincrement)
		foreign_id (INT 10)
		foreign_type (ENUM['object','catalog'])
		author (INT 10, references id on 'users')
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

	URL: /objects 
	Type: POST  
	Parameters: catalog_id, category_id, type_id, name, description, url, retail_price, sale_price, competitor_flag, recomended, curated, _token  
	Returns:  
		- response with type: success
		- response with type: error

## /objects/{id}

Update object properties (users can only updated objects owned by them).  

Note: not mandatory fields can be sent separately. For example to change under which catalog an object belongs, it is enough to send only the catalog_id value (and of course the _token).  

	URL: /objects/{id} 
	Type: PUT  
	Parameters (URL): id
	Parameters (POST): catalog_id, category_id, type_id, name, description, url, retail_price, sale_price, competitor_flag, recomended, curated, _token  
	Returns:  
		- response with type: success
		- response with type: error

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

	URL: /catalogs 
	Type: POST  
	Parameters: name, title, _token  
	Returns:  
		- response with type: success
		- response with type: error

## /catalogs/{id}

Update catalog properties (users can only updated catalogs owned by them).

	URL: /catalogs/{id} 
	Type: PUT  
	Parameters (URL): id
	Parameters (POST): name, title, _token 
	Returns:  
		- response with type: success
		- response with type: error

## /catalogs/{id}/objects

Display all objects residing in the catalog (empty if none).

	URL: /catalogs/{id}/objects  
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

# Responses

All responses are JSON encoded strings. Each response will return the "_token" value to use with CSRF protection.

## Response type: success

	{
		"type":"success",
		"message":"Some success message",
		"_token":"IFMJy3kdsaReScNLLrNuNNixT59A6aJ3ghHAgeuL"
	}

## Response type: error

	{
		"type":"error",
		"message":"Some error message",
		"_token":"IFMJy3kdsaReScNLLrNuNNixT59A6aJ3ghHAgeuL"
	}

## Response type: result

With singular content:  

	{
		"type":"result",
		"content":13,
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
		"_token":"IFMJy3kdsaReScNLLrNuNNixT59A6aJ3ghHAgeuL"
	}

## -