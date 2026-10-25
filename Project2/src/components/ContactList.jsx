import ContactItem from "./ContactItem";

function ContactList({
  contacts,
  totalContacts,
  searchTerm,
  onDeleteContact
}) {
  if (totalContacts === 0) {
    return (
      <section className="contact-list">
        <h2>My Contacts</h2>
        <p>No contacts yet. Add one above!</p>
      </section>
    );
  }

  if (contacts.length === 0) {
    return (
      <section className="contact-list">
        <h2>My Contacts</h2>
        <p>No matches found for "{searchTerm}".</p>
      </section>
    );
  }

  return (
    <section className="contact-list">
      <h2>My Contacts</h2>

      {contacts.map((contact) => (
        <ContactItem
          key={contact.id}
          contact={contact}
          onDeleteContact={onDeleteContact}
        />
      ))}
    </section>
  );
}

export default ContactList;