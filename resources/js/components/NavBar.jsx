import React, { useEffect, useState } from 'react';
import ReactDOM from 'react-dom/client';

const App = () => 
{
    const [offset, setOffset] = useState(0);
    const [transparent, setTransparency] = useState(false);
    const onScroll = useCallback(() => { 
       setOffset(window.pageYOffset)
       
        if (offset > 64)
        {
            setTransparency(false);
        }
        else
        {
            setTransparency(true);
        }
    }, [offset]);

    useEffect(() => 
    {
        window.addEventListener('scroll', onScroll);
        
        return () => window.removeEventListener('scroll', onScroll);
    }, [onScroll]);

    var bg = "bg-gray-800";

    if (transparent)
    {
        bg = "bg-gray-800/75";
    }

    const classes = 'fixed z-10 top-0 left-0 h-16 w-full shadow ' + bg;

    return (
        <nav class={classes}>
            <div class="hidden h-full md:block" id="navbar-default">
                <ul class="h-full flex flex-col justify-center items-center text-white md:flex-row md:space-x-8 md:mt-0 md:text-sm md:font-medium md:border-0">
                    <li>
                        <a href="/" class="block py-2 pl-3 pr-4">Home</a>
                    </li>
                    <li>
                        <a href="https://github.com/orgs/bestmods/discussions/categories/feedback-ideas" target="_blank" class="block py-2 pl-3 pr-4">Feedback</a>
                    </li>
                    <li>
                        <a href="https://github.com/bestmods/roadmap/issues" target="_blank" class="block py-2 pl-3 pr-4">Roadmap</a>
                    </li>
                    <li>
                        <a href="https://github.com/BestMods/bestmods" target="_blank" class="block py-2 pl-3 pr-4">Source</a>
                    </li>
                    <li>
                        <a href="https://github.com/orgs/bestmods/discussions/2" target="_blank" class="block py-2 pl-3 pr-4">Removals</a>
                    </li>
                    <li>
                        <a href="https://moddingcommunity.com/" target="_blank" class="block py-2 pl-3 pr-4">Mod Community</a>
                    </li>
                </ul>
            </div>
        </nav>
    );
};

const content = ReactDOM.createRoot(document.getElementById('navbarapp'));
content.render(<App/>);