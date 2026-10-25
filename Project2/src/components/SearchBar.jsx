function SearchBar({ searchTerm, onSearchChange }) {
  return (
    <section className="search-bar">
      <label htmlFor="search">Search Contacts</label>

      <input
        id="search"
        type="search"
        placeholder="Search by name or email..."
        value={searchTerm}
        onChange={(event) => onSearchChange(event.target.value)}
      />
    </section>
  );
}

export default SearchBar;