export default function Pagination() {
    return (
        <div className="flex justify-center">
            <div className="flex items-center space-x-2">
                <button className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-l">
                    Previous
                </button>
                <button className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-r">
                    Next
                </button>
            </div>
        </div>
    );
}
