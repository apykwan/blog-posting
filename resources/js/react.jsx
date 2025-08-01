import { createRoot } from "react-dom/client";

import SearchBar from "./react-components/SearchBar";
import ChatBtn from './react-components/ChatBtn';
import ChatWrapper from './react-components/ChatWrapper';

// const reactSearch = document.getElementById("react-search");
// const root = ReactDOM.createRoot(reactSearch);
// root.render(<SearchBar />);

const chatBtn = document.getElementById("react-chat-btn")
if (chatBtn) createRoot(chatBtn).render(<ChatBtn />)

const wrapper = document.getElementById("react-chat-wrapper")
if (wrapper) {
  const username = wrapper.dataset.username

  createRoot(document.getElementById("react-chat-root")).render(
    <ChatWrapper username={username} />
  );
}