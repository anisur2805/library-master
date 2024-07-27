import { useState, useEffect } from "react";
import axios from "axios";
import TableHeader from "./components/TableHeader";
import Table from "./components/Table";
import { filteredModifiedBooks } from "./helper";

function Frontend() {
    const [books, setBooks] = useState([]);
    const [filterBooks, setFilterBooks] = useState([]);
    const [searchTerm, setSearchTerm] = useState("");
    const [message, setMessage] = useState("");

    const url = `${app.root}library/v1/books`;

    useEffect(() => {
        axios
            .get(url, {
                headers: {
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

    useEffect(() => {
        setFilterBooks(books);
    }, [books]);

    const handleSubmit = () => {
        if (searchTerm === "") {
            setMessage("No search term found");
            return;
        }

        const filteredBooks = filteredModifiedBooks(books, searchVal);
        setFilterBooks(filteredBooks);
    };

    const handleInputChange = (e) => {
        const searchVal = e.target.value.toLowerCase();
        setSearchTerm(searchVal);

        const filteredBooks = filteredModifiedBooks(books, searchVal);

        setTimeout(() => {
            setFilterBooks(filteredBooks);
        }, 1000);

        if (filteredBooks.length === 0) {
            setMessage("No matching books found");
        } else {
            setMessage("");
        }
    };

    return (
        <div className="ce-book-library-frontend">
            <TableHeader
                handleSubmit={handleSubmit}
                handleInputChange={handleInputChange}
                searchTerm={searchTerm}
            />
            <Table filteredBooks={filterBooks} message={message} />
        </div>
    );
}

export default Frontend;
