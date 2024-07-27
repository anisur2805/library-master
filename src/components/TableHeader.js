export default function TableHeader({handleSubmit, handleInputChange, searchTerm}) {
    return (
        <div className="flex justify-between items-center mb-6">
            <h4 className="!m-0 text-5xl font-bold">Book Library Table</h4>
            <form
                action="#"
                method="GET"
                className="flex"
                onSubmit={handleSubmit}
            >
                <input
                    type="text"
                    name="s"
                    value={searchTerm}
                    onChange={handleInputChange}
                    placeholder="Search title, author, isbn..."
                    className="min-w-[300px] px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                />
                <button
                    type="submit"
                    className="min-w-[100px] px-4 py-1 bg-blue-500 text-white rounded-r-md hover:bg-blue-600 hover:no-underline"
                >
                    Search
                </button>
            </form>
        </div>
    );
}
