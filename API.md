# API
This project has an API which you can use for more flexible access. The API provides endpoints for accessing various resources such as services, requests, posts, events, users, and sessions. The API follows RESTful principles and supports HTTP methods like GET, POST, PATCH, and DELETE. 

## Authentication

Certain API endpoints require authentication. To authenticate create a new session (see [Sessions](#sessions)) and pass the resulting token via the "Authorization: Bearer" header or via a Cookie named "session". Users of type 'admin' have access to all the API endpoints unrestricted.

## Request body

Certain API endpoints require a request body. The following content types are supported:
- JSON (application/json)
- Multipart form data (multipart/formdata)
- Query strings

Notes:
- Form data is only supported for POST requests.
- To upload a photo using JSON or query strings base64 encode the file and put it in the respective request, e.g.:
```
"photos": [
  {
    "data": "...",
    "filename: "img.jpg"
  }, ...
]
```

## Endpoints

### Services

#### Get all services
- Endpoint: `GET /api/services?limit={limit}&offset={offset}`
- Parameters:
  - `limit` (optional, integer): Number of services to retrieve (default: all)
  - `offset` (optional, integer): Offset for pagination (default: 0)

#### Get a single service
- Endpoint: `GET /api/services/{serviceID}`
- Parameters:
  - `serviceID` (integer): ID of the service to retrieve
  
#### Get all services of a servicer
- Endpoint: `GET /api/services/user/{userID}?limit={limit}&offset={offset}`
- Parameters:
  - `userID` (integer): Servicer ID
  - `limit` (optional, integer): Number of services to retrieve (default: all)
  - `offset` (optional, integer): Offset for pagination (default: 0)

#### Search services by title or tags
- Endpoint: `GET /api/services?q={searchQuery}&limit={limit}&offset={offset}`
- Parameters:
  - `searchQuery` (string): Search query to filter services
  - `limit` (optional, integer): Number of services to retrieve (default: all)
  - `offset` (optional, integer): Offset for pagination (default: 0)

#### Create a new service
- Endpoint: `POST /api/services`
- Body: (object representing the new service)

#### Partially modify an existing service
- Endpoint: `PATCH /api/services/{serviceID}`
- Parameters:
  - `serviceID` (integer): ID of the service to modify
- Body: (object containing the fields to update)

#### Delete a single service
- Endpoint: `DELETE /api/services/{serviceID}`
- Parameters:
  - `serviceID` (integer): ID of the service to delete

### Requests

#### Get all requests
- Endpoint: `GET /api/requests?limit={limit}&offset={offset}`
- Parameters:
  - `limit` (optional, integer): Number of requests to retrieve (default: all)
  - `offset` (optional, integer): Offset for pagination (default: 0)
  
#### Get a single request
- Endpoint: `GET /api/requests/{requestID}`
- Parameters:
  - `requestID` (integer): ID of the request to retrieve

#### Get all outgoing requests submitted by user
- Endpoint: `GET /api/requests/user/{userID}?limit={limit}&offset={offset}`
- Parameters:
  - `userID` (integer): User ID
  - `limit` (optional, integer): Number of requests to retrieve (default: all)
  - `offset` (optional, integer): Offset for pagination (default: 0)

#### Get all incoming requests submitted to a servicer
- Endpoint: `GET /api/requests/user/{userID}/incoming?limit={limit}&offset={offset}`
- Parameters:
  - `userID` (integer): Servicer ID
  - `limit` (optional, integer): Number of requests to retrieve (default: all)
  - `offset` (optional, integer): Offset for pagination (default: 0)

#### Create a new request
- Endpoint: `POST /api/requests`
- Body: (object representing the new request)

#### Partially modify an existing request
- Endpoint: `PATCH /api/requests/{requestID}`
- Parameters:
  - `requestID` (integer): ID of the request to modify
- Body: (object containing the fields to update)

#### Delete a single request
- Endpoint: `DELETE /api/requests/{requestID}`
- Parameters:
  - `requestID` (integer): ID of the request to delete

### Posts

#### Get all posts
- Endpoint: `GET /api/posts?limit={limit}&offset={offset}`
- Parameters:
  - `limit` (optional, integer): Number of posts to retrieve (default: all)
  - `offset` (optional, integer): Offset for pagination (default: 0)

#### Get a single post
- Endpoint: `GET /api/posts/{postID}`
- Parameters:
  - `postID` (integer): ID of the post to retrieve

#### Get all posts of a servicer
- Endpoint: `GET /api/posts/user/{userID}?limit={limit}&offset={offset}`
- Parameters:
  - `userID` (integer): Servicer ID
  - `limit` (optional, integer): Number of posts to retrieve (default: all)
  - `offset` (optional, integer): Offset for pagination (default: 0)

#### Create a new post
- Endpoint: `POST /api/posts`
- Body: (object representing the new post)

#### Partially modify an existing post
- Endpoint: `PATCH /api/posts/{postID}`
- Parameters:
  - `postID` (integer): ID of the post to modify
- Body: (object containing the fields to update)

#### Delete a single post
- Endpoint: `DELETE /api/posts/{postID}`
- Parameters:
  - `postID` (integer): ID of the post to delete

### Events

#### Get all events
- Endpoint: `GET /api/events?limit={limit}&offset={offset}`
- Parameters:
  - `limit` (optional, integer): Number of events to retrieve (default: all)
  - `offset` (optional, integer): Offset for pagination (default: 0)

#### Get a single event
- Endpoint: `GET /api/events/{eventID}`
- Parameters:
  - `eventID` (integer): ID of the event to retrieve
  
#### Get all events of a user
- Endpoint: `GET /api/events/user/{userID}?limit={limit}&offset={offset}`
- Parameters:
  - `userID` (integer): User ID
  - `limit` (optional, integer): Number of events to retrieve (default: all)
  - `offset` (optional, integer): Offset for pagination (default: 0)

#### Get all events of a user scheduled in a specific month
- Endpoint: `GET /api/events/user/{userID}?month={month}&year={year}`
- Parameters:
  - `userID` (integer): User ID
  - `month` (integer): Month to retrieve events from
  - `year` (integer): Year to retrieve events from
  
#### Create a new event
- Endpoint: `POST /api/events`
- Body: (object representing the new event)

#### Partially modify an existing event
- Endpoint: `PATCH /api/events/{eventID}`
- Parameters:
  - `eventID` (integer): ID of the event to modify
- Body: (object containing the fields to update)

#### Delete a single event
- Endpoint: `DELETE /api/events/{eventID}`
- Parameters:
  - `eventID` (integer): ID of the event to delete

### Users

#### Get all users
- Endpoint: `GET /api/users?limit={limit}&offset={offset}`
- Parameters:
  - `limit` (optional, integer): Number of users to retrieve (default: all)
  - `offset` (optional, integer): Offset for pagination (default: 0)

#### Get a single user
- Endpoint: `GET /api/users/{userID}`
- Parameters:
  - `userID` (integer): ID of the user to retrieve
  
#### Get all users by type
- Endpoint: `GET /api/users?type={userType}&limit={limit}&offset={offset}`
- Parameters:
  - `userType` (string): User type (either "user" or "servicer")
  - `limit` (optional, integer): Number of users to retrieve (default: all)
  - `offset` (optional, integer): Offset for pagination (default: 0)

#### Create a new user
- Endpoint: `POST /api/users`
- Body: (object representing the new user)

#### Partially modify an existing user
- Endpoint: `PATCH /api/users/{userID}`
- Parameters:
  - `userID` (integer): ID of the user to modify
- Body: (object containing the fields to update)

#### Delete a single user
- Endpoint: `DELETE /api/users/{userID}`
- Parameters:
  - `userID` (integer): ID of the user to deleted

### Sessions

#### Create a new session
- Endpoint: `POST /api/sessions`
- Body: (username/email & password)