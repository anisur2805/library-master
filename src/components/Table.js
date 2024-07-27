export default function Table({filteredBooks, message }) {
    return (
        <div className="bg-white shadow rounded-lg overflow-hidden">
            <table className="m-0 min-w-full divide-y divide-gray-200">
                <thead className="bg-gray-50">
                    <tr>
                        <th className="text-3xl font-bold px-6 py-4 text-left text-black-500 uppercase tracking-wider">
                            Title
                        </th>
                        <th className="text-3xl font-bold px-6 py-4 text-left text-black-500 uppercase tracking-wider">
                            Author
                        </th>
                        <th className="text-3xl font-bold px-6 py-4 text-left text-black-500 uppercase tracking-wider">
                            Publisher
                        </th>
                        <th className="text-3xl font-bold px-6 py-4 text-left text-black-500 uppercase tracking-wider">
                            ISBN
                        </th>
                        <th className="text-3xl font-bold px-6 py-4 text-left text-black-500 uppercase tracking-wider">
                            Publication Date
                        </th>
                    </tr>
                </thead>
                <tbody className="bg-white divide-y divide-gray-200">
                    {filteredBooks.length === 0 ? (
                        <tr>
                            <td
                                className="px-6 py-4 whitespace-nowrap text-[16px] font-bold px-6 py-4 text-center text-red-500"
                                colSpan="5"
                            >
                                {message}
                            </td>
                        </tr>
                    ) : (
                        filteredBooks.map((book) => (
                            <tr key={book.id}>
                                <td className="px-6 py-4 whitespace-nowrap text-[16px] font-bold px-6 py-4">
                                    {book.title}
                                </td>
                                <td className="px-6 py-4 whitespace-nowrap text-[16px] font-bold px-6 py-4">
                                    {" "}
                                    {book.author}
                                </td>
                                <td className="px-6 py-4 whitespace-nowrap text-[16px] font-bold px-6 py-4">
                                    {" "}
                                    {book.publisher}
                                </td>
                                <td className="px-6 py-4 whitespace-nowrap text-[16px] font-bold px-6 py-4">
                                    {" "}
                                    {book.isbn}
                                </td>
                                <td className="px-6 py-4 whitespace-nowrap text-[16px] font-bold px-6 py-4">
                                    {" "}
                                    {book.publication_date}
                                </td>
                            </tr>
                        ))
                    )}
                </tbody>
            </table>
        </div>
    );
}
