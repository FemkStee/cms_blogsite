<style>
    .dashboard {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .dashboard-container {
        display: flex;
        justify-content: space-between;
    }
    .dashboard-ratings {
        text-align: center;
        width: 50%;
    }
    .dashboard-settings {
        text-align: center;
        width: 50%;
    }
    .rating {
        background-color: gainsboro;
        width: 50%;
        margin: 0.5rem auto;
        border-radius: 8px;
        padding: 0.25rem;
    }
</style>
<div class="dashboard">
    <h1 class="">Ratings Dashboard</h1>
    <div class="dashboard-container">
        <div class="dashboard-ratings">
            <h2>Ratings</h2>
            <div>
                {{ ratings }}
                <div class="rating">
                    <p>{{ emojis[rating] }} ({{rating}})</p>
                    <p>User : {{ id }}</p>
                </div>
                {{ /ratings }}
            </div>
        </div>
        <div class="dashboard-settings">
            <h2>Settings</h2>
        </div>
    </div>
    {{ratings}}
</div>