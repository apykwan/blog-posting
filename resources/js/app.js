import './bootstrap';
import Search from './live-search';
import Chat from './chat';

if (document.querySelector('.header-search-icon')) new Search();
if (document.querySelector('.header-chat-icon')) new Chat();

console.log(import.meta.env.VITE_NODE_SERVER_PORT);