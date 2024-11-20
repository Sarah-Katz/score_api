# Score API Documentation

**This documentation provides details on how to use the Score API, which manages teams, players, games, and scores.**

## Endpoints

### Teams

#### Get All Teams

- **URL:** /teams
- **Method:** GET
- **Description:** Retrieves a list of all teams.
- **Response:** An array of team objects containing id, name, players, and scores.

#### Create a Team

- **URL:** /teams
- **Method:** POST
- **Description:** Creates a new team.
- **Response:** The created team object.
- **Request Body:**

```json
{
  "name": "Team Name"
}
```

#### Get a Specific Team

- **URL:** /teams/{id}
- **Method:** GET
- **Description:** Retrieves details of a specific team.
- **Parameters:**
***id*** (path parameter): The ID of the team.
- **Response:** A team object containing id, name, players, and scores.

#### Delete a Team

- **URL:** /teams/{id}
- **Method:** DELETE
- **Description:** Deletes a specific team.
- **Parameters:**
***id*** (path parameter): The ID of the team to delete.
- **Response:** A success message.

### Games

#### Get All Games

- **URL:** /games
- **Method:** GET
- **Description:** Retrieves a list of all games.
- **Response:** An array of game objects.

#### Create a Game

- **URL:** /games
- **Method:** POST
- **Description:** Creates a new game.
- **Response:** The created game object.
- **Request Body:** Should be empty, games only contain an id

#### Get a Specific Game

- **URL:** /games/{id}
- **Method:** GET
- **Description:** Retrieves details of a specific game.
- **Parameters:**
***id*** (path parameter): The ID of the game.
- **Response:** A game object.

#### Delete a Game

- **URL:** /games/{id}
- **Method:** DELETE
- **Description:** Deletes a specific game.
- **Parameters:**
***id*** (path parameter): The ID of the game to delete.
- **Response:** A success message.

### Scores

#### Get All Scores

- **URL:** /scores
- **Method:** GET
- **Description:** Retrieves a list of all scores.
- **Response:** An array of score objects.

#### Create a Score

- **URL:** /scores
- **Method:** POST
- **Description:** Creates a new score.
- **Response:** The created score object.
- **Request Body:**

```json
{
  "value": 10,
  "game": 1,
  "team": 1
}
```

#### Get a Specific Score

- **URL:** /scores/{id}
- **Method:** GET
- **Description:** Retrieves details of a specific score.
- **Parameters:**
***id*** (path parameter): The ID of the score.
- **Response:** A score object.

#### Update a Score

- **URL:** /scores/{id}
- **Method:** PUT
- **Description:** Updates a specific score.
- **Parameters:**
***id*** (path parameter): The ID of the score to update.
- **Response:** The updated score object.
- **Request Body:**

```json
{
  "value": 15,
  "game": 1,
  "team": 1
}
```

#### Delete a Score

- **URL:** /scores/{id}
- **Method:** DELETE
- **Description:** Deletes a specific score.
- **Parameters:**
***id*** (path parameter): The ID of the score to delete.
- **Response:** A success message.

### Players

#### Get All Players

- **URL:** /players
- **Method:** GET
- **Description:** Retrieves a list of all players.
- **Response:** An array of player objects.

#### Create a Player

- **URL:** /players
- **Method:** POST
- **Description:** Creates a new player.
- **Response:** The created player object.
- **Request Body:**

```json
{
  "name": "Player Name",
  "team": 1
}
```

#### Get a Specific Player

- **URL:** /players/{id}
- **Method:** GET
- **Description:** Retrieves details of a specific player.
- **Parameters:**
***id*** (path parameter): The ID of the player.
- **Response:** A player object.

#### Update a Player

- **URL:** /players/{id}
- **Method:** PUT
- **Description:* Updates a specific player.
- **Parameters:**
***id*** (path parameter): The ID of the player to update.
- **Response:** The updated player object.
- **Request Body:**

```json
{
  "name": "Updated Player Name",
  "team": 2
}
```

#### Delete a Player

- **URL:** /players/{id}
- **Method:** DELETE
- **Description:** Deletes a specific player.
- **Parameters:**
***id*** (path parameter): The ID of the player to delete.
- **Response:** A success message.

## Data Structures

### Team Object

```json
{
  "id": 1,
  "name": "Team Name",
  "players": [
    {
      "id": 1,
      "name": "Player Name"
    }
  ],
  "scores": [
    {
      "id": 1,
      "value": 10,
      "game": 2
    }
  ]
}
```

### Games Object

```json
{
  "id": 2,
    "scores": [
      {
        "value": 3,
        "team": 
          {
            "id": 3,    
            "name": "The big Johns"
          }
      }
    ]
}
```

### Scores Object

```json
{
  "id": 1,
  "value": 3,
  "game":
    {
       "id": 2
    },
  "team":
    {
      "id": 3,
      "name": "The big Johns"
    }
}
```

### Players Object

```json
{
  "id": 3,
  "name": "Johnny",
  "team":
    {
      "id": 3,
      "name": "The big Johns"
    }
}
```

## Error Handling

The API uses standard HTTP status codes to indicate the success or failure of requests. In case of errors, a JSON response with an error message will be returned.
