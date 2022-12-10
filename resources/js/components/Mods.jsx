import React, { useCallback, useState } from 'react';
import ReactDOM from 'react-dom/client';
import InfiniteScroll from 'react-infinite-scroller';

import parse from 'html-react-parser';

async function loadMore(page)
{
    var data = await fetch('/retrieve?page=' + page).then(res => res.text());

    data = data.replace(/&lt;/g, '<');
    data = data.replace(/&gt;/g, '>');

    console.log(data);

    return JSON.parse(data);
}

const App = () => 
{
    const [items, setItems] = useState([]);
    const [pageNum, setPageNum] = useState(0);
    const [fetching, setFetching] = useState(false);

    // If our page number is below one, set to one.
    if (pageNum < 1)
    {
        setPageNum(1);
    }

    const fetchItems = useCallback(
        async () => {
            // If we're already fetching. Return.
            if (fetching) 
            {
                return;
            }

            // We're fetching!
            setFetching(true);

            // Try to fetch new lines/data.
            try
            {
                console.log("Retrieving new items on page " + pageNum + "!");

                // Call our load more function.
                const newItems = await loadMore(pageNum);

                // Now set our items.
                setItems([...items, ...newItems]);
            }
            finally
            {
                // Increase page number and set fetching to false.
                setPageNum(pageNum + 1);

                setFetching(false);
            }
        },

        [items, fetching, pageNum]
    );

    return (
    <InfiniteScroll
        loadMore={fetchItems}
        hasMore={true}
        loader={<div class="loadindicator">Loading...</div>}>
            <table id="modstable" class="card">
                <thead>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Game</th>
                    <th>Seed</th>
                    <th>Stats</th>
                    <th>Buttons</th>
                </thead>

                <tbody>
                    {items.map(item => (
                        <tr class="{{item.gclasses}} {{item.sclasses}}" key={item.id}>
                            <td class="card-image-td">{parse(item.image)}</td>
                            <td class="card-name-td">{parse(item.name)}</td>
                            <td class="card-desc-td">{parse(item.description)}</td>
                            <td class="card-game-td">{parse(item.game)}</td>
                            <td class="card-seed-td">{parse(item.seed)}</td>
                            <td class="card-stats-td">{parse(item.stats)}</td>
                            <td class="card-buttons-td">{parse(item.buttons)}</td>
                        </tr>
                    ))}
                </tbody>
            </table>
    </InfiniteScroll>);
};

const content = ReactDOM.createRoot(document.getElementById('mods'));
content.render(<App/>);