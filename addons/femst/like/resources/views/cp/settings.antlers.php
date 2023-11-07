<style>
    .dashboard {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        text-align: center;
    }
    .dashboard-overview {
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .dashboard-overview-item {
        margin: 10px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        text-decoration: none;
        width: 10%;
        color: #000;
    }
    .card__entryId {
        margin: 10px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        text-decoration: none;
        width: 10%;
        color: #000;
    }
    .card__entryId:hover {
        background-color: #ccc;
    }
    .addbtn {
        text-decoration: none;
        color: #000;
        font-size: 30px;
    }
</style>
<div class="dashboard">
    <h1 class="">Ratings Dashboard</h1>
    <div class="dashboard-overview">
        <div class="dashboard-overview-item">
            <p>Total Ratings</p>
            <p>{{totalRatings}}</p>
        </div>
        <div class="dashboard-overview-item">
            <p>Total Average Rating</p>
            <p>{{averageRating}}</p>
        </div>
        <div class="dashboard-overview-item">
            <p>Add New Rating</p>
            <a class="addbtn" href="add">+</a>
        </div>
    </div>
    <div>
        <h2>All Entry's</h2>
        {{ foreach:allEntryIds }}
            <a class="card__entryId" href="/cp/like/{{value}}">{{value}}</a>
        {{ /foreach:allEntryIds }}
</div>
</div>