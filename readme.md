## Catalog API

# Models

**User**  

	route: /users  
	Properties:
		id (INT 10 - primary key, autoincrement)
		tags (VARCHAR 255, nullable, default: null)
		name (VARCHAR 255)
		email (VARCHAR 255, unique)
		password (VARCHAR 60 - bcrypted)
		remember_token (VARCHAR 100)
		image (VARCHAR 255, nullable, default: null)
		commissions_earned (INT 10, default: 0)
		comission_rate (INT 10, default: 0)
		personal_price_earned (INT 10, default: 0)
		price_earner (INT 10, default: 0)
		created_at (TIMESTAMP)
		updated_at (TIMESTAMP)
		
**Object**  

	route: /objects
	Properties:
		id (INT 10 - primary key, autoincrement)
		catalog_id (INT 10 - references id on 'catalogs', default: 0)
		category_id (INT 10 - references id on 'categories', default: 0)
		type_id (INT 10 - references id on 'types', default: 0)
		tags (VARCHAR 255, nullable, default: null)
		name (VARCHAR 255)
		description (TEXT, nullable, default: null)
		url (VARCHAR 255, nullable, default: null)
		image (VARCHAR 255, nullable, default: null)
		weight (DOUBLE(12,4), nullable, default: null)
		retail_price (DOUBLE(12,2), nullable, default: null)
		sale_price (DOUBLE(12,2), nullable, default: null)
		offer_value (DOUBLE(12,2), nullable, default: null)
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
		competitor_flag (ENUM[0,1], default: 0)
		recomended (ENUM[0,1], default: 0)
		curated (ENUM[0,1], default: 0)
		author (INT 10, references id on 'users')
		status (ENUM[-1,0,1], default: 1)
		created_at (TIMESTAMP)
		updated_at (TIMESTAMP)
		
**Catalog**  

	route: /catalogs
	Properties:
		id (INT 10 - primary key, autoincrement)
		category_id (INT 10 - references id on 'categories', default: 0)
		type_id (INT 10 - references id on 'types', default: 0)
		tags (VARCHAR 255, nullable, default: null)
		name (VARCHAR 255)
		title (VARCHAR 255)
		description (TEXT, nullable, default: null)
		layout (VARCHAR 55, nullable, default: null)
		position (VARCHAR 55, nullable, default: null)
		publish (ENUM[0,1], default: 0)
		trending (ENUM[0,1], default: 0)
		popular (ENUM[0,1], default: 0)
		recomended (ENUM[0,1], default 0)
		count_likes (INT 10, default: 0)
		count_comments (INT 10, default: 0)
		count_follows (INT 10, default: 0)
		total_transaction (DOUBLE(12,2), default: 0)
		author (INT 10, references id on 'users')
		status (ENUM[-1,0,1], default: 1)
		created_at (TIMESTAMP)
		updated_at (TIMESTAMP)  
		
**Collection**  

	route: /collections
	Properties:
		id (BIGINT 20 - primary key, autoincrement)
		collection_id (INT 10)
		foreign_id (INT 10)
		foreign_type (ENUM['object','catalog'])
		author (INT 10, references id on 'users')
		status (ENUM[-1,0,1], default: 1)
		created_at (TIMESTAMP)
		updated_at (TIMESTAMP)  
		
**Category**

	route: /categories
	Properties:
		id (INT 10 - primary key, autoincrement)
		name (VARCHAR 55)
		created_at (TIMESTAMP)
		updated_at (TIMESTAMP)  
		
**Types**

	route: /types
	Properties:
		id (INT 10 - primary key, autoincrement)
		name (VARCHAR 55)
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
		
**Follows**  

	route: /follows
	Properties:
		id (INT 10 - primary key, autoincrement)
		foreign_id (INT 10)
		foreign_type (ENUM['object','catalog'])
		author (INT 10, references id on 'users')
		created_at (TIMESTAMP)
		updated_at (TIMESTAMP) 
		
**Feedbacks**  

	route: /feedbacks
	Properties:
		id (INT 10 - primary key, autoincrement)
		foreign_id (INT 10)
		foreign_type (ENUM['object','catalog'])
		value (BIGINT 20)
		author (INT 10, references id on 'users')
		created_at (TIMESTAMP)
		updated_at (TIMESTAMP)  
		
**Activities**  

	route: /activities
	Properties:
		id (INT 10 - primary key, autoincrement)
		catalog_id (INT 10, references id on 'catalogs')
		type_id (INT 10, references id on 'types')
		name (VARCHAR 255)
		description (TEXT, nullable, default: null)
		link_to (INT 10, default: 0)
		link_from (INT 10, default: 0)
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

## /users/{id}

Show one particular user.

	URL: /users/{id} 
	Type: GET  
	Parameters: id  
	Returns:  
		- response with type: result (UserObject)
		- response with type: error (user not found)  

## /users/{id}

Update user properties (users can only updated their own properties).  

> Note: not mandatory fields can be sent separately.  

> Note: tags are divided by ";" (tag1;tag2;tag3;...;tagn)

	URL: /users/{id} 
	Type: PUT  
	Parameters (URL): id
	Parameters (POST): tags, name, image, commissions_earned, commission_rate, personal_price_earned, price_earner, _token  
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

## /users/{id}/follows

List all follows made by the user.

	URL: /users/{id}/follows  
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
	Parameters: catalog_id, category, type, tags, name, description, url, image, weight, retail_price, sale_price, offer_value, offer_url, offer_description, offer_start, offer_stop, prod_detail_url, layout, position, competitor_flag, recomended, curated, _token  
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
	Parameters (POST): catalog_id, category, type, tags, name, description, url, image, weight, retail_price, sale_price, offer_value, offer_url, offer_description, offer_start, offer_stop, prod_detail_url, layout, position, competitor_flag, recomended, curated, _token  
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

## /objects/{id}/feedback

Feedback on an object.

	URL: /objects/{id}/feedback  
	Type: POST
	Parameters (URL): id
	Parameters (POST): value, _token
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
	Parameters: category, type, tags, name, title, description, layout, position, publish, trending, popular, recomended, _token  
	Returns:  
		- response with type: success
		- response with type: error

## /catalogs/{id}

Update catalog properties (users can only updated catalogs owned by them).  

> Note: the "type" property can be a numeric id or string name. If type name is given, the API will try to locate that within the existing types and assign the id accordingly. If the given type name doesn't exists in the database, it will be created and the id assigned accordingly.

> Note: the "category" property can be a numeric id or string name. If category name is given, the API will try to locate that within the existing categories and assign the id accordingly. If the given category name doesn't exists in the database, it will be created and the id assigned accordingly.

> Note: tags are divided by ";" (tag1;tag2;tag3;...;tagn)

	URL: /catalogs/{id} 
	Type: PUT  
	Parameters (URL): id
	Parameters (POST): category, type, tags, name, title, description, layout, position, publish, trending, popular, recomended, _token 
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

## /catalogs/{id}/feedback

Feedback on a catalogs.

	URL: /catalogs/{id}/feedback  
	Type: POST
	Parameters (URL): id
	Parameters (POST): value, _token
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

## /collections/{id}

Delete a collection (a user can only delete his collections)

	URL: /collections/{id}
	Type: DELETE
	Parameters: id
	Returns:
		- response with type: success
		- response with type: error (collection not found)

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

Delete a collection.

	URL: /collections/{id} 
	Type: DELETE  
	Parameters (URL): collection_id
	Parameters (POST): _token  
	Returns:  
		- response with type: success
		- response with type: error  

## /deleted/collections

View collections deleted by the user

	URL: /deleted/collections
	Type: GET
	Returns:
		- response with type: result ([CollectionObjects])

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
	Parameters (POST): catalof, type, name, description, link_to, link_from, _token 
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

## -