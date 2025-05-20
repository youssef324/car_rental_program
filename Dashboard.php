<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard</title>
  <link rel="stylesheet" href="style/dashboard.css" />
</head>

<body>
  <!-- Header with Search and Dropdowns -->
  <header class="header">
    <a href="about_us.html" title="car-rental">
      <h1 class="header-h1">Impact Makers</h1>
    </a>
    <!-- Dropdowns for filtering -->
    <div class="dropdowns">
      <select class="dropdown">
        <option value="Type">Type</option>
        <option value="Sports">Sports</option>
        <option value="Sedan">Sedan</option>
        <option value="Suv">SUV</option>
        <option value="Hatchback">Hatchback</option>
      </select>
      <select class="dropdown">
        <option value="">All</option>
        <?php
        // Connect to database
        $conn = new mysqli("localhost", "root", "", "carrentalsystem");

        // Check connection
        if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
        }

        // Get distinct years from database
        $sql = "SELECT DISTINCT Year FROM cars ORDER BY Year DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['Year'] . "'>" . $row['Year'] . "</option>";
          }
        }
        ?>
      </select>
      <select class="dropdown">
        <option value="">All</option>
        <?php
        // Get distinct brands from model names
        $sql = "SELECT Model FROM cars";
        $result = $conn->query($sql);
        $brands = array();

        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $brand = explode(" ", $row['Model'])[0];
            if (!in_array($brand, $brands)) {
              $brands[] = $brand;
              echo "<option value='" . $brand . "'>" . $brand . "</option>";
            }
          }
        }
        ?>
      </select>
      <select class="dropdown">
        <option value="">Price</option>
        <option value="Above $2000">Above $2000</option>
        <option value="$1500~$2000">$1500~$2000</option>
        <option value="$1000~$1500">$1000~$1500</option>
        <option value="$500~$1000">$500~$1000</option>
        <option value="Below $500">Below $500</option>
      </select>
      <!-- Search bar -->
      <input type="text" placeholder="Search for a car..." class="search-input" />
    </div>
  </header>

  <!-- Sidebar Navigation -->
  <div class="sidebar">
    <nav class="nav">
      <a href="./profile.php" title="My Profile">
        <img src="Photos/account.png" alt="My Account" />
      </a>
      <a href="./Dashboard.php" title="Cars">
        <img src="Photos/car.png" alt="Cars" />
      </a>
      <a href="./contacts.html" title="Contacts">
        <img src="Photos/mail.png" alt="Contact Us" />
      </a>
      <a href="./about_us.html" title="About Us">
        <img src="Photos/about.png" alt="About Us" />
      </a>
      <a href="./index.html" title="Log Out">
        <img src="Photos/logout.png" alt="Log Out" />
      </a>
    </nav>
  </div>

  <!-- Main Section with Cards -->
  <main class="main-content">
    <?php
    // Fetch available cars
    $sql = "SELECT * FROM cars WHERE Status = 'active'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0):
      while ($car = $result->fetch_assoc()):
        ?>
        <div class="card">
          <img src="<?php echo $car['image_url']; ?>" alt="<?php echo $car['Model']; ?>" id="image_url" class="card-image" />
          <div class="card-content">
            <h3 class="car-model"><?php echo $car['Model']; ?></h3>
            <div class="details">
              <div class="left-side">
                <span class="car-price">Price: $<?php echo number_format($car['PricePerDay']); ?></span><br>
                <span class="model-year">Model Year: <?php echo $car['Year']; ?></span>
              </div>
              <button class="rent">
                <span><a href="details.php?CarID=<?php echo $car['CarID']; ?>">Rent</a></span>
              </button>
            </div>
          </div>
        </div>
        <?php
      endwhile;
    else:
      echo "<p>No cars available at the moment.</p>";
    endif;
    ?>
  </main>


  <script>
    document.addEventListener("DOMContentLoaded", function () {
      // Get all elements we need
      const searchInput = document.querySelector(".search-input");
      const dropdowns = document.querySelectorAll(".dropdown");
      const cards = document.querySelectorAll(".card");

      // Function to filter cards based on search and dropdown selections
      function filterCards() {
        const searchTerm = searchInput.value.toLowerCase();
        const typeValue = document.querySelectorAll(".dropdown")[0].value;
        const yearValue = document.querySelectorAll(".dropdown")[1].value;
        const brandValue = document.querySelectorAll(".dropdown")[2].value;
        const priceValue = document.querySelectorAll(".dropdown")[3].value;

        cards.forEach((card) => {
          const carModel = card.querySelector(".car-model").textContent.toLowerCase();
          const modelYear = card.querySelector(".model-year").textContent.split(": ")[1];
          const carPrice = parseInt(
            card.querySelector(".car-price").textContent.split("$")[1].replace(",", "")
          );

          // Check if the car matches all filter criteria
          const matchesSearch = carModel.includes(searchTerm);
          const matchesType =
            typeValue === "Type" ||
            (typeValue === "Sports" && isSportsCar(carModel)) ||
            (typeValue === "Sedan" && isSedan(carModel)) ||
            (typeValue === "Suv" && isSUV(carModel)) ||
            (typeValue === "Hatchback" && isHatchback(carModel));
          const matchesYear = !yearValue || modelYear === yearValue;
          const matchesBrand =
            brandValue === "All" || carModel.startsWith(brandValue.toLowerCase());

          const matchesPrice =
            !priceValue || checkPriceRange(priceValue, carPrice);

          if (matchesSearch && matchesType && matchesYear && matchesBrand && matchesPrice) {
            card.classList.remove('hidden');
          } else {
            card.classList.add('hidden');
          }
        });
      }

      // Helper functions to determine car types
      function isSportsCar(model) {
        const sportsModels = [
          "bugatti",
          "koeingsegg",
          "pagani",
          "ferrari",
          "lamborghini",
          "porsche 918",
          "mclaren",
        ];
        return sportsModels.some((sportModel) => model.includes(sportModel));
      }

      function isSedan(model) {
        const sedanModels = [
          "mercedes c180",
          "bmw m3",
          "aston martin",
          "rollsroyce",
        ];
        return sedanModels.some((sedanModel) => model.includes(sedanModel));
      }

      function isSUV(model) {
        const suvModels = [
          "dodge durango",
          "mercedes g class",
          "lamborghini urus",
          "bmw x6",
        ];
        return suvModels.some((suvModel) => model.includes(suvModel));
      }

      function isHatchback(model) {
        const hatchbackModels = ["opel corsa", "brilliance"];
        return hatchbackModels.some((hatchbackModel) =>
          model.includes(hatchbackModel)
        );
      }

      // Helper function to check price ranges
      function checkPriceRange(priceRange, price) {
        switch (priceRange) {
          case "Above $2000":
            return price > 2000;
          case "$1500~$2000":
            return price >= 1500 && price <= 2000;
          case "$1000~$1500":
            return price >= 1000 && price < 1500;
          case "$500~$1000":
            return price >= 500 && price < 1000;
          case "Below $500":
            return price < 500;
          default:
            return true;
        }
      }

      // Add event listeners
      searchInput.addEventListener("input", filterCards);
      dropdowns.forEach((dropdown) => {
        dropdown.addEventListener("change", filterCards);
      });
    });
  </script>
</body>

</html>