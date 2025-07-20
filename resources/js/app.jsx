import React from "react";
import ReactDOM from "react-dom/client";

import "./bootstrap.js";
import Search from "./live-search.js";
import Chat from "./chat.js";
import SearchBar from './react-components/SearchBar';

const app = document.getElementById("react-search");

if (app) {
    const root = ReactDOM.createRoot(app);
    root.render(<SearchBar />);
}

if (document.querySelector(".header-search-icon")) new Search();
if (document.querySelector(".header-chat-icon")) new Chat();
