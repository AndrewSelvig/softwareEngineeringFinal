document.addEventListener("DOMContentLoaded", function() {
    loadCategories();
    loadDeals();
});

function loadCategories() {
    // Assuming the categories data is available via an API or static JSON file
    fetch('categories.json')  // Modify with the actual path to your categories data
        .then(response => response.json())
        .then(categories => {
            const categoryContainer = document.getElementById('category-buttons');
            categories.forEach(category => {
                let button = document.createElement('button');
                button.innerHTML = `<img src="${category.image_url}" alt="${category.name}">`;
                categoryContainer.appendChild(button);
            });
        })
        .catch(error => console.error('Error loading categories:', error));
}

function loadDeals() {
    // Assuming the deals data is available via an API or static JSON file
    fetch('deals.json')  // Modify with the actual path to your deals data
        .then(response => response.json())
        .then(deals => {
            const dealsContainer = document.getElementById('deals-container');
            deals.forEach((deal, index) => {
                if (index < 10) {  // Load only the first 10 deals
                    let dealDiv = document.createElement('div');
                    dealDiv.className = 'deal';
                    dealDiv.innerHTML = `
                        <img src="${deal.image_url}" alt="Product Image">
                        <p>${deal.description}</p>
                    `;
                    dealsContainer.appendChild(dealDiv);
                }
            });
        })
        .catch(error => console.error('Error loading deals:', error));
}
