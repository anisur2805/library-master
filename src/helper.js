const filteredModifiedBooks = (books, searchTerm) => {
    return books.filter(book =>
        book.title.toLowerCase().includes(searchTerm) ||
        book.author.toLowerCase().includes(searchTerm) ||
        book.isbn.toLowerCase().includes(searchTerm) ||
        book.publisher.toLowerCase().includes(searchTerm) ||
        book.publication_date.toLowerCase().includes(searchTerm)
    );
};

export { filteredModifiedBooks }