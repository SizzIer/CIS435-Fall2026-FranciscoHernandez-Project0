import { useState } from "react";
import ContactForm from "./components/ContactForm";
import ContactList from "./components/ContactList";
import SearchBar from "./components/SearchBar";
import "./App.css";

function App() {
  const [contacts, setContacts] = useState([]);
  const [searchTerm, setSearchTerm] = useState("");

  function addContact(newContact) {
    setContacts((previousContacts) => [
      ...previousContacts,
      newContact,
    ]);
  }

  function deleteContact(id) {
    setContacts((previousContacts) =>
      previousContacts.filter((contact) => contact.id !== id)
    );
  }

  const filteredContacts = contacts.filter((contact) => {
    const search = searchTerm.toLowerCase();

    return (
      contact.name.toLowerCase().includes(search) ||
      contact.email.toLowerCase().includes(search)
    );
  });

  return (
    <main className="app">
      <h1>Simple Contact Book</h1>
      <p>Manage your contacts in one place.</p>

      <ContactForm onAddContact={addContact} />

      <SearchBar
        searchTerm={searchTerm}
        onSearchChange={setSearchTerm}
      />

      <ContactList
        contacts={filteredContacts}
        totalContacts={contacts.length}
        searchTerm={searchTerm}
        onDeleteContact={deleteContact}
      />
    </main>
  );
}

export default App;