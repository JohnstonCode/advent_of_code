package main

import (
	"fmt"
	"log"
	"os"
	"sort"
	"strconv"
	"strings"
)

type Range struct {
	start, end int
}

func main() {
	ranges, ids := parseInput("input.txt")

	fmt.Println(part1(ranges, ids))
	fmt.Println(part2(ranges))
}

func parseInput(filename string) ([]Range, []int) {
	input, err := os.ReadFile(filename)
	if err != nil {
		log.Fatal(err)
	}

	parts := strings.Split(string(input), "\n\n")
	rawRanges := strings.Split(parts[0], "\n")
	rawIds := strings.Split(parts[1], "\n")

	ranges := make([]Range, 0)
	for _, rng := range rawRanges {
		pts := strings.Split(rng, "-")
		start, _ := strconv.Atoi(pts[0])
		end, _ := strconv.Atoi(pts[1])

		ranges = append(ranges, Range{start, end})
	}

	ids := make([]int, 0)

	for _, id := range rawIds {
		iId, _ := strconv.Atoi(id)

		ids = append(ids, iId)
	}

	return ranges, ids
}

func part1(ranges []Range, ids []int) int {
	fresh := 0

	for _, id := range ids {
		for _, rng := range ranges {
			if id >= rng.start && id <= rng.end {
				fresh++

				break
			}
		}
	}

	return fresh
}

func part2(ranges []Range) int {
	sort.Slice(ranges, func(i, j int) bool {
		return ranges[i].start < ranges[j].start
	})

	merged := []Range{ranges[0]}

	for i := 1; i < len(ranges); i++ {
		last := &merged[len(merged)-1]

		if ranges[i].start <= last.end {
			if ranges[i].end > last.end {
				last.end = ranges[i].end
			}
		} else {
			merged = append(merged, ranges[i])
		}
	}

	total := 0

	for _, mrg := range merged {
		total += (mrg.end - mrg.start) + 1
	}

	return total
}
