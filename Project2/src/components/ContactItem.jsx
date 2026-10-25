function ContactItem({ contact, onDeleteContact }) {
  return (
    <article className="contact-item">
      <h3>{contact.name}</h3>
      <p>Email: {contact.email}</p>
      <p>Phone: {contact.phone || "Not provided"}</p>

      <button
        type="button"
        onClick={() => onDeleteContact(contact.id)}
      >
        Delete
      </button>
    </article>
  );
}

export default ContactItem;