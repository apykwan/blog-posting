import { useEffect, useState } from 'react';

export default function SearchBar() {
  const [searchTerm, setSearchTerm] = useState('');
  const [posts, setPosts] = useState([]);

  useEffect(() => {
    if (searchTerm.trim() === "") {
      setPosts([]);
      return;
    }
    const searchTimer = setTimeout(async () => {
      const { data } = await axios(`http://localhost:8000/search/${searchTerm}`);
      setPosts(data);
    }, 750);

   return () => clearTimeout(searchTimer);
  }, [searchTerm]);

  return (
    <div className="d-flex align-items-center" style={{ height: "50px" }}>
      <input
          className=""
          type="text"
          onChange={(e) => setSearchTerm(e.target.value)}
      />

      <ul>
          {posts.length > 0 &&
              posts.map((post) => <li key={post.title}>{post.title}</li>)}
      </ul>
    </div>
  );
}
