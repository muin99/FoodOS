<?php
$pageTitle = 'BiteBuddy - Browse Restaurants';
$activePage = 'browse';
$basePath = '../../';
$extraCss = ['../assets/css/browseResturants.css'];
include __DIR__ . '/../../dirCommon/header.php';
?>

    <main class="page-wrap">
        <section class="page-title">
            <div>
                <h1>Browse Restaurants</h1>
                <p>Find nearby places and order your favorite meals.</p>
            </div>
            <form class="search-box">
                <label for="restaurant-search">Search</label>
                <input id="restaurant-search" type="search" placeholder="Restaurant or dish name">
            </form>
        </section>

        <section class="filters" aria-label="Restaurant filters">
            <button class="active" type="button">All</button>
            <button type="button">Pizza</button>
            <button type="button">Burgers</button>
            <button type="button">Sushi</button>
            <button type="button">Thai</button>
            <button type="button">Healthy</button>
        </section>

        <section class="restaurant-section">
            <div class="section-heading">
                <h2>Restaurants Near You</h2>
                <span>8 results</span>
            </div>

            <div class="restaurant-grid">
                <article class="restaurant-card">
                    <img src="../assets/images/burger-culture.svg" alt="Burger Culture food preview">
                    <div class="card-body">
                        <div class="card-top">
                            <h3>Burger Culture</h3>
                            <span class="rating">4.8</span>
                        </div>
                        <p>American, Burgers, Grill</p>
                        <div class="meta">
                            <span>20-30 min</span>
                            <span>Free delivery</span>
                        </div>
                    </div>
                </article>

                <article class="restaurant-card">
                    <img src="../assets/images/sakura-sushi.svg" alt="Sakura Sushi food preview">
                    <div class="card-body">
                        <div class="card-top">
                            <h3>Sakura Sushi</h3>
                            <span class="rating">4.9</span>
                        </div>
                        <p>Japanese, Sushi, Healthy</p>
                        <div class="meta">
                            <span>35-45 min</span>
                            <span>$2.99 delivery</span>
                        </div>
                    </div>
                </article>

                <article class="restaurant-card">
                    <img src="../assets/images/luigis-pizzeria.svg" alt="Luigi's Pizzeria food preview">
                    <div class="card-body">
                        <div class="card-top">
                            <h3>Luigi's Pizzeria</h3>
                            <span class="rating">4.5</span>
                        </div>
                        <p>Italian, Pizza, Pasta</p>
                        <div class="meta">
                            <span>15-25 min</span>
                            <span>Free delivery</span>
                        </div>
                    </div>
                </article>

                <article class="restaurant-card">
                    <img src="../assets/images/thai-express.svg" alt="Thai Express food preview">
                    <div class="card-body">
                        <div class="card-top">
                            <h3>Thai Express</h3>
                            <span class="rating">4.7</span>
                        </div>
                        <p>Thai, Curry, Asian</p>
                        <div class="meta">
                            <span>25-40 min</span>
                            <span>$1.50 delivery</span>
                        </div>
                    </div>
                </article>

                <article class="restaurant-card">
                    <img src="../assets/images/green-bowl.svg" alt="The Green Bowl food preview">
                    <div class="card-body">
                        <div class="card-top">
                            <h3>The Green Bowl</h3>
                            <span class="rating">4.9</span>
                        </div>
                        <p>Healthy, Vegan, Bowls</p>
                        <div class="meta">
                            <span>10-20 min</span>
                            <span>Free delivery</span>
                        </div>
                    </div>
                </article>

                <article class="restaurant-card">
                    <img src="../assets/images/taco-fiesta.svg" alt="Taco Fiesta food preview">
                    <div class="card-body">
                        <div class="card-top">
                            <h3>Taco Fiesta</h3>
                            <span class="rating">4.6</span>
                        </div>
                        <p>Mexican, Street Food</p>
                        <div class="meta">
                            <span>20-30 min</span>
                            <span>Free delivery</span>
                        </div>
                    </div>
                </article>

                <article class="restaurant-card">
                    <img src="../assets/images/brunch-club.svg" alt="Brunch Club food preview">
                    <div class="card-body">
                        <div class="card-top">
                            <h3>Brunch Club</h3>
                            <span class="rating">4.8</span>
                        </div>
                        <p>Breakfast, Pancakes, Coffee</p>
                        <div class="meta">
                            <span>25-35 min</span>
                            <span>$3.50 delivery</span>
                        </div>
                    </div>
                </article>

                <article class="restaurant-card">
                    <img src="../assets/images/grill-house.svg" alt="The Grill House food preview">
                    <div class="card-body">
                        <div class="card-top">
                            <h3>The Grill House</h3>
                            <span class="rating">4.4</span>
                        </div>
                        <p>Steakhouse, Modern, Grill</p>
                        <div class="meta">
                            <span>40-55 min</span>
                            <span>Free delivery</span>
                        </div>
                    </div>
                </article>
            </div>
        </section>
    </main>

<?php include __DIR__ . '/../../dirCommon/footer.php'; ?>
