<div class="left-col">
    <div class="list">
        <h3>Catefories</h3>
        <ul class="bookmark-categories" id="bookmark-categories">
            <?php
                if(isset($_SESSION['loggedIn']))
                {
            ?>
                <li class="selected" id="yours">Your's</li>
                <li id="everyone">Everyone's</li>
                <li id="new">New</li>
                <li id="popular">Popular</li>
            <?php
                }
                else
                {
            ?>
                <li id="everyone" class="selected">Everyone's</li>
                <li id="new">New</li>
                <li id="popular">Popular</li>
            <?php
                }
            ?>
        </ul>
    </div>
</div>