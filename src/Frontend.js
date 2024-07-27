import React, { useState, useEffect } from "react";
import axios from "axios";
import { IoSearchSharp } from "react-icons/io5";
import TableHeader from "./components/TableHeader";
import Table from "./components/Table";
import Pagination from "./components/Pagination";

function Frontend() {
    const [books, setBooks] = useState([]);
    const [searchTerm, setSearchTerm] = useState("");
    const [message, setMessage] = useState("");
    const [error, setError] = useState(null);
    const [loader, setLoader] = useState("Save setting");

    const url = `${app.root}library/v1/books`;

    useEffect(() => {
        axios
            .get(url, {
                headers: {
                    Authorization: `Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjEwMDAzIiwiaWF0IjoxNzIxOTkwMzA3LCJuYmYiOjE3MjE5OTAzMDcsImV4cCI6MTcyMjU5NTEwNywiZGF0YSI6eyJ1c2VyIjp7ImlkIjoiMSJ9fX0.LXH8qmwlUEbeCVh7pEaRwOZLeoUfRjwrdxGhRNQkO-Y`,
                    "Content-Type": "application/json",
                },
            })
            .then((res) => {
                setBooks(res.data);
            })
            .catch((error) => {
                console.error("Error fetching data:", error);
                setLoader("");
            });
    }, [url, searchTerm]);

    const handleSubmit = (e) => {
        e.preventDefault();

        if (searchTerm) {
            const filteredBooks = books.filter(
                (book) =>
                    book.title
                        .toLowerCase()
                        .includes(searchTerm.toLowerCase()) ||
                    book.author
                        .toLowerCase()
                        .includes(searchTerm.toLowerCase()) ||
                    book.isbn
                        .toLowerCase()
                        .includes(searchTerm.toLowerCase()) ||
                    book.publication_date
                        .toLowerCase()
                        .includes(searchTerm.toLowerCase())
            );
            setBooks(filteredBooks);
        } else {
            setMessage("No search term found");
        }
    };

    const handleInputChange = (e) => {
        e.preventDefault();

        setSearchTerm(e.target.value);

        const filteredBooks = books.filter(
            (book) =>
                book.title.toLowerCase().includes(searchTerm.toLowerCase()) ||
                book.author.toLowerCase().includes(searchTerm.toLowerCase()) ||
                book.isbn.toLowerCase().includes(searchTerm.toLowerCase()) ||
                book.publication_date.toLowerCase().includes(searchTerm.toLowerCase())
        );

        if (filteredBooks.length === 0) {
            setMessage("No matching books found");
        } else {
            setMessage("");
        }
    };

    const filteredBooks = books.filter(
        (book) =>
            book.title.toLowerCase().includes(searchTerm.toLowerCase()) ||
            book.author.toLowerCase().includes(searchTerm.toLowerCase()) ||
            book.isbn.toLowerCase().includes(searchTerm.toLowerCase()) ||
            book.publication_date
                .toLowerCase()
                .includes(searchTerm.toLowerCase())
    );

    return (
        <div className="ce-book-library-frontend">
            <TableHeader handleSubmit={handleSubmit} handleInputChange={handleInputChange} searchTerm={searchTerm} />
            <Table filteredBooks={filteredBooks} message={message} />
            <Pagination />
        </div>
    );
}

export default Frontend;
