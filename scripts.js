document.addEventListener("DOMContentLoaded", function() {
    // Example data arrays
    const categories = [
        { name: "TVs", icon: "tv-icon.png" },
        { name: "Audio", icon: "audio-icon.png" },
        { name: "Video Games", icon: "games-icon.png" },
        { name: "Home Appliances", icon: "appliances-icon.png" },
        { name: "Cellphones", icon: "cellphones-icon.png" },
        { name: "Laptops", icon: "laptops-icon.png" }
    ];

    const deals = [
        { image: "deal1.png", description: "Discount on 4K TVs" },
        { image: "deal2.png", description: "Save on Bluetooth speakers" },
        // Add more deals as needed, up to 10
    ];

    // Load categories dynamically
    const categoryContainer = document.getElementById('category-buttons');
    categories.forEach(category => {
        let button = document.createElement('button');
        button.innerHTML = `<img src="${category.icon}" alt="${category.name}">`;
        categoryContainer.appendChild(button);
    });

    // Load deals dynamically
    const dealsContainer = document.getElementById('deals-container');
    deals.forEach(deal => {
        let dealDiv = document.createElement('div');
        dealDiv.className = 'deal';
        dealDiv.innerHTML = `<img src="${deal.image}" alt="Deal Image"><p>${deal.description}</p>`;
        dealsContainer.appendChild(dealDiv);
    });
});
