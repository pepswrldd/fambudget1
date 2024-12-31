const sideMenu = document.querySelector('aside');
const menuBtn = document.querySelector('#menu_bar');
const closeBtn = document.querySelector('#close_btn');

const themeToggler = document.querySelector('.theme-toggler'); // Light Dark Themew
const square = document.querySelector('.square');
const btnAct = document.querySelector('#btn-activate');


menuBtn.addEventListener('click',()=>{
       sideMenu.style.display = "block"
})
closeBtn.addEventListener('click',()=>{
    sideMenu.style.display = "none"
})

themeToggler.addEventListener('click', () => {
    document.body.classList.toggle('dark-theme-variables');
    themeToggler.querySelector('span:nth-child(1)').classList.toggle('active');
    themeToggler.querySelector('span:nth-child(2)').classList.toggle('active');
});



const btnSideList = document.querySelectorAll('.sideoptions');
//thıs dorsnt work again
btnSideList.forEach(btnSide => {
    btnSide.addEventListener('click', () => {
        document.querySelector('.special')?.classList.remove('special');
        btnSide.classList.add('special');
    })
})





btnAct.addEventListener('click', () => {
    square.classList.toggle('hidden1')
})



function changeClass(){
    var element = document.querySelector('#myDiv');
    element.classList.replace('oldClass','newClass');
}





/*  Products   */






// Enter Expense
function popupFn() {
    document.getElementById("overlay").style.display = "block";
    document.getElementById("popupDialog").style.display = "block";
}

function closeFn() {
    document.getElementById("overlay").style.display = "none";
    document.getElementById("popupDialog").style.display = "none";
}

// Enter New Earnings
function popupFn2() {
    document.getElementById("overlay2").style.display = "block";
    document.getElementById("popupDialog2").style.display = "block";
}

function closeFn2() {
    document.getElementById("overlay2").style.display = "none";
    document.getElementById("popupDialog2").style.display = "none";
}

// Overlay'e tıklandığında da modalları kapat
document.getElementById("overlay").addEventListener("click", closeFn);
document.getElementById("overlay2").addEventListener("click", closeFn2);

//logout
function confirmLogout(event) {
    event.preventDefault(); // Prevent default action
    if (confirm("Are you sure you want to log out?")) {
      window.location.href = "logout.php"; // Redirect to logout.php
    }
  };  

//expense
document.getElementById('expenseForm').addEventListener('submit', async function (event) {
    event.preventDefault();

    const name = document.getElementById('expense-name').value;
    const price = document.getElementById('expense-price').value;
    const theme = document.getElementById('theme').value;
    const details = document.getElementById('details').value;

    try {
        const response = await fetch('submit_expense.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({ name, price, theme, details })
        });
        const data = await response.text();
        alert(data);
        document.getElementById('expenseForm').reset();
    } catch (error) {
        console.error('Error:', error);
    }
});


//earnings
document.getElementById('earningsForm').addEventListener('submit', function (event) {
    event.preventDefault(); // Prevent form submission

    // Get form data
    const amount = document.getElementById('earnings-amount').value;

    // Send data to the backend
    fetch('submit_earnings.php', {
        method: 'POST',
        body: new URLSearchParams({
            'amount': amount
        })
    })
    .then(response => response.text())
    .then(data => {
        alert(data); // Display the server response (success or error message)
        document.getElementById('earningsForm').reset(); // Reset form
    })
    .catch(error => console.error('Error:', error));
});

//change password
document.getElementById('changePasswordForm').addEventListener('submit', function (event) {
    const newPassword = document.getElementById('new-password').value;
    const confirmPassword = document.getElementById('confirm-password').value;

    if (newPassword !== confirmPassword) {
        alert('Passwords do not match.');
        event.preventDefault();
    }
});

//dynamically load family members from a database using an AJAX request.
document.addEventListener("DOMContentLoaded", function() {
    fetch('get_family_members.php')
        .then(response => response.json())
        .then(data => {
            const familyContainer = document.getElementById('family-container');
            familyContainer.innerHTML = ''; // Clear any existing content

            data.forEach(member => {
                const memberDiv = document.createElement('div');
                memberDiv.classList.add('family-member');
                memberDiv.innerHTML = `
                    <h2>${member.name}</h2>
                    <input type="number" placeholder="Add Spending" class="spending-input">
                    <button class="add-btn" data-id="${member.id}">Add Spending</button>
                    <p>Total Spendings: <span class="total-spendings">$${member.total_spendings}</span></p>
                `;
                familyContainer.appendChild(memberDiv);
            });
        })
        .catch(error => console.error('Error fetching family members:', error));
});



//Adding Spending for a Family Member
document.addEventListener('click', (event) => {
    if (event.target.classList.contains('add-btn')) {
        const memberId = event.target.dataset.id;
        const input = event.target.previousElementSibling.value;

        if (!input || isNaN(input) || input <= 0) {
            alert('Please enter a valid amount.');
            return;
        }

        fetch('add_spending.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ memberId, amount: input })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Spending added successfully!');
                location.reload(); // Refresh to update spending
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => console.error('Error adding spending:', error));
    }
});

//Add New Family Member modal
document.addEventListener("DOMContentLoaded", function() {
    // Fetch family members from the database
    fetch('get_family_members.php')
        .then(response => response.json())
        .then(data => {
            const familyContainer = document.getElementById('family-container');
            familyContainer.innerHTML = ''; // Clear any existing content

            data.forEach(member => {
                const memberDiv = document.createElement('div');
                memberDiv.classList.add('family-member');
                memberDiv.innerHTML = `
                    <h2>${member.name}</h2>
                    <input type="number" placeholder="Add Spending" class="spending-input">
                    <button class="add-btn" data-id="${member.id}">Add Spending</button>
                    <p>Total Spendings: <span class="total-spendings">$${member.total_spendings}</span></p>
                `;
                familyContainer.appendChild(memberDiv);
            });
        })
        .catch(error => console.error('Error fetching family members:', error));

    // Show the modal when the "Add New Family Member" button is clicked
    document.getElementById('add-member-btn').addEventListener('click', function() {
        document.getElementById('add-member-modal').style.display = 'block';
    });

    // Close the modal when the close button is clicked
    document.getElementById('close-modal').addEventListener('click', function() {
        document.getElementById('add-member-modal').style.display = 'none';
    });

    // Add family member using the modal form
    document.getElementById('add-member-form').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the form from submitting normally
        const name = document.getElementById('member-name').value;

        if (name) {
            fetch('add_family_member.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ name })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Family member added!');
                    location.reload(); // Refresh to show new member
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => console.error('Error adding family member:', error));

            // Close the modal after adding the member
            document.getElementById('add-member-modal').style.display = 'none';
        } else {
            alert('Please enter a name.');
        }
    });
});
