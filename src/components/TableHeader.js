export default function TableHeader({
    handleSubmit,
    handleInputChange,
    searchTerm,
}) {
    return (
        <div className="flex flex-col sm:flex-row justify-between items-center mb-6 space-y-4 sm:space-y-0">
            <h4 className="!m-0 text-3xl sm:text-4xl md:text-5xl font-bold mb-4 sm:mb-0 sm:w-1/3 text-center sm:text-left">
                Book Library
            </h4>
            <div className="flex w-full sm:w-[340px] items-center">
                <label htmlFor="search" className="sr-only">
                    Search Books
                </label>
                <input
                    type="text"
                    id="search"
                    value={searchTerm}
                    onChange={handleInputChange}
                    placeholder="Search title, author, isbn..."
                    className="flex-grow sm:flex-grow-0 w-full sm:w-[230px] px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-black placeholder-gray-500"
                />
                <button
                    type="button"
                    onClick={handleSubmit}
                    className="flex-shrink-0 px-4 py-2 bg-blue-500 text-white rounded-r-md hover:bg-blue-600"
                    style={{ height: "54px" }}
                >
                    Search
                </button>
            </div>
        </div>
    );
}
