import React from "react";
import ReactDOM from "react-dom/client";

import "./bootstrap.js";
import Search from "./live-search.js";
import Chat from "./chat.js";
import SearchBar from './react-components/SearchBar';

// SearchBar
const reactSearch = document.getElementById("react-search");
if (reactSearch) {
  const root = ReactDOM.createRoot(reactSearch);
  root.render(<SearchBar />);
}

if (document.querySelector(".header-search-icon")) new Search();
if (document.querySelector(".header-chat-icon")) new Chat();
