<!-- Improved compatibility of back to top link: See: https://github.com/othneildrew/Best-README-Template/pull/73 -->
<a name="readme-top"></a>
<!--
*** Thanks for checking out the Best-README-Template. If you have a suggestion
*** that would make this better, please fork the repo and create a pull request
*** or simply open an issue with the tag "enhancement".
*** Don't forget to give the project a star!
*** Thanks again! Now go create something AMAZING! :D
-->


<!-- PROJECT LOGO -->
<br />
<div align="center">
<h3 align="center">Payment API Demo</h3>

  <p align="center">
    Payment API Demo built for XSolla
  </p>
</div>



<!-- TABLE OF CONTENTS -->
<details>
  <summary>Table of Contents</summary>
  <ol>
    <li>
      <a href="#getting-started">Getting Started</a>
      <ul>
        <li><a href="#prerequisites">Prerequisites</a></li>
        <li><a href="#installation">Installation</a></li>
      </ul>
    </li>
    <li><a href="#usage">API Schema</a></li>
    <li><a href="#roadmap">Roadmap</a></li>
  </ol>
</details>


<!-- GETTING STARTED -->
## Getting Started

### Prerequisites

* docker [Docker Installation](https://docs.docker.com/engine/install/)

### Installation

1. Clone the repo
   ```sh
   git clone https://github.com/Sakthyvell/laravel-api.git
   ```
2. Build and run docker image
   ```sh
   docker compose up -d
   ```
3. To access the docker terminal
   ```sh
   docker exec -it <container-id> bash
   ```
3. Run migration to update database
   ```sh
   cd /var/www/html/
   php artisan migrate:refresh --seed
   ```

<p align="right">(<a href="#readme-top">back to top</a>)</p>



<!-- USAGE EXAMPLES -->
## API Schema

### Obtaining a list of legal entities

```
Method : GET
Endpoint : localhost:80/api/entity
```

### Checking the legal entity's balance

```
Method : GET
Endpoint : localhost:80/api/entity/<entity-id>
```

### Create transaction
```
Method : POST
Endpoint : localhost:80/api/transaction
```

#### Request
```json
{
    "entity_id": "<entity-id>"
}
```

### Update Transaction
```
Method : PUT
Endpoint : localhost:80/api/transaction
```

#### Request
```json
{
    "action" : "withdraw",
    "amount" : "<amount>"
}
```
```json
{
    "action" : "deposit",
    "amount" : "<amount>"
}
```

### Checking company balance
```
Method : GET
Endpoint : localhost:80/api/company
```

<p align="right">(<a href="#readme-top">back to top</a>)</p>


<!-- ROADMAP -->
## Roadmap

- [ ] Adding OAuth to APIs
- [ ] Implement callback feature

<p align="right">(<a href="#readme-top">back to top</a>)</p>


<!-- LICENSE -->
## License

Distributed under the MIT License. See `LICENSE.txt` for more information.

<p align="right">(<a href="#readme-top">back to top</a>)</p>